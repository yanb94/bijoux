# Jewelry ecommerce (portfolio)

This repository contains a ecommerce about jewels that is part of my portfolio.
The website is build with Sylius in its version 1.8.

## Limitations

The docker environnement who contains the website can only work on localhost with ssl certificate.
If you want to use another domain you need to change the virtual host in the apache folder and generate a new ssl certificate corresponding to your new domain by your own way.

I'm a french freelancer who target mainly client in france so the website will be only in french.

## Requirements

You need to have Docker with compose and a stripe account with public and private keys.

## Installation

### Clone the project

Clone the project in the directory you want to execute it.

```sh
cd /path/to/myfolder
git clone https://github.com/yanb94/bijoux.git
```

### Create an environnement file for the docker environnement

Create an environnent file for docker who contains the password for the database and the possibility to enable or not opcache revalidation of file base on timestamp.

```env
#.env
DB_PASSWORD=<your_database_password>
OPCACHE_REVALIDATE=1 #Recommended to leave on 1 for the first utilisation or if you plane to change the content
```

### Create an environnement file for the website

Create a local environnement file for the website who contains:

-   The environnement to execute the website (prod recommended)
-   The app secret for symfony (You can generate one [here](https://coderstoolbox.online/toolbox/generate-symfony-secret))
-   The url of the database
-   The mailer url (See symfony documentation for [more informations](https://symfony.com/doc/current/mailer.html))
-   The path of wkhtmlToPdf
-   The path of wkhtmlToImage
-   Your stripe public key
-   Your stripe private key
-   Your passphrase for JWT (You can generate one on the same site as for Symfony secret [here](https://coderstoolbox.online/toolbox/generate-symfony-secret))

```env
#web/.env.local
APP_ENV=prod
APP_SECRET=<your_app_secret>
DATABASE_URL=mysql://root:<your_database_password>@mysql/sylius_%kernel.environment%
MAILER_URL=<your_mailer_url>
WKHTMLTOPDF_PATH=/usr/bin/wkhtmltopdf
WKHTMLTOIMAGE_PATH=/usr/bin/wkhtmltoimage
STRIPE_PUBLIC_KEY=<your_stripe_public_key>
STRIPE_PRIVATE_KEY=<your_stripe_private_key>
JWT_PASSPHRASE=<your_passphrase_for_jwt>
```

### Start docker container

```sh
cd /path/to/myfolder
docker-compose up
```

### Open a new terminal in php-fpm docker container

```
cd /path/to/myfolder
docker-compose exec php-fpm bash
```

You will have to type all the following installation command in this opened terminal.

### Install the composer dependencies

```sh
composer update
```

### Run the Sylius install command

```sh
php bin/console sylius:install
```

When the process will ask to load example sample data, respons No.

On the step 3, of this process type the following data:

-   currency: EUR
-   language: fr_FR
-   email: <your_email>
-   username: <your_username>
-   password: <your_password>

### Install the Sylius javacript dependencies

```sh
yarn install
yarn build
```

### Install the asset of the custom theme

```sh
yarn encore prod
```

### Create key for JWT authentification

```sh
php bin/console lexik:jwt:generate-keypair
```

### Update the database schema

```sh
php bin/console doctrine:schema:update --force
```

### Load sample data

```sh
php bin/console doctrine:fixture:load --group=init --append
```

## Usage

For any question related to the use of the backoffice (add products, taxonomy, etc..) you can refer to [the sylius documentation](https://docs.sylius.com/en/1.8/getting-started-with-sylius/basic-configuration.html).

## Troubleshooting

In the first start of the docker environnement the mysql container can take a long time to completely start.
This can create an issue where you can see during the installation the error message "Connection Refused".
The solution to this issue is to wait until the complete initialization of the container data and retry.
You can see when the container is ready in the docker log associate when you see this line and no more logs of the container without action:

```
[System] [MY-010931] [Server] /usr/sbin/mysqld: ready for connections. Version: '8.0.29'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server - GPL
```
