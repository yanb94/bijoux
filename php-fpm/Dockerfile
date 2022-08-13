FROM php:7.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    gcc \ 
    g++ \ 
    make \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libyaml-dev \
    libzip4 \
    libzip-dev \
    zlib1g-dev \
    libicu-dev \
    libmagickwand-dev \
    g++ \
    git \
    cron \
    vim \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-configure exif \
    && docker-php-ext-install exif \
    && docker-php-ext-enable exif \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install apcu \
    && pecl install yaml-2.0.4 \
    && docker-php-ext-enable apcu yaml

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install NodeJs
RUN apt-get install -y curl \
  && curl -sL https://deb.nodesource.com/setup_15.x | bash - \
  && apt-get install -y nodejs \
  && curl -L https://www.npmjs.com/install.sh | sh

# Install yarn
RUN npm install --global yarn

RUN apt-get update -qq \
  && apt-get install --no-install-recommends -yq build-essential wkhtmltopdf python3-pip python2 xvfb xauth xfonts-base \
  && apt-get remove -y build-essential \
  && apt-get autoremove -y \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && truncate -s 0 /var/log/*log

COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./timezone.ini /usr/local/etc/php/conf.d/timezone.ini
COPY ./memory_limit.ini /usr/local/etc/php/conf.d/memory_limit.ini

RUN useradd yanb -m
USER yanb

RUN git config --global user.email "20875343+yanb94@users.noreply.github.com" \
  && git config --global user.name "Yann-Elias BELLAGNECH"


WORKDIR /var/www/html