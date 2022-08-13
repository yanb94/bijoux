<?php

namespace App\DataFixtures;

use App\Entity\Taxonomy\Taxon;
use App\Entity\Channel\Channel;
use App\Entity\Product\Product;
use App\DataFixtures\InitFixtures;
use App\DataFixtures\TaxonFixtures;
use App\Entity\Product\ProductImage;
use App\Entity\Product\ProductTaxon;
use App\Entity\Taxation\TaxCategory;
use App\DataFixtures\ChannelsFixtures;
use App\Entity\Channel\ChannelPricing;
use App\Entity\Product\ProductVariant;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Shipping\ShippingCategory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends InitFixtures implements DependentFixtureInterface
{
    private $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function load(ObjectManager $manager): void
    {
        $channelRepository = $manager->getRepository(Channel::class);
        /** @var Channel */
        $channel = $channelRepository->findOneBy(["code" => "default"]);

        $taxonRepository = $manager->getRepository(Taxon::class);
        $taxons = $taxonRepository->findBy(["enabled" => true]);

        $listTaxon = [];

        foreach ($taxons as $taxon) {
            $listTaxon[$taxon->getCode()] = $taxon;
        }

        $shippingCategoryRepository = $manager->getRepository(ShippingCategory::class);
        $shippingCategory = $shippingCategoryRepository->findOneBy(["code" => "BIJOUX"]);

        $taxCategoryRepository = $manager->getRepository(TaxCategory::class);
        $taxCategory = $taxCategoryRepository->findOneBy(["code" => "VAT"]);

        foreach ($this->getListOfProduct() as $productData) {
            $product = $this->createProduct(
                $channel, 
                $listTaxon[$productData["taxon"]],
                $shippingCategory,
                $taxCategory,
                $productData
            );

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function createProduct(
        Channel $channel, 
        Taxon $taxon,
        ShippingCategory $shippingCategory,
        TaxCategory $taxCategory,
        array $data
    ): Product
    {
        $productTaxon = new ProductTaxon();
        $productTaxon->setTaxon($taxon);

        $channelPricing = new ChannelPricing();
        $channelPricing->setPrice($data['price']);
        $channelPricing->setChannelCode($channel->getCode());

        $productVariant = new ProductVariant();

        $productVariant->setShippingRequired(true);
        $productVariant->setShippingCategory($shippingCategory);
        $productVariant->setTaxCategory($taxCategory);
        $productVariant->addChannelPricing($channelPricing);
        $productVariant->setCode($data['code']);

        $imagePath = realpath(__DIR__ . '/../../imageFixtures/'.$data['image']);

        $uploadedFile = new UploadedFile($imagePath, $imagePath, null, null, false);

        $productImage = new ProductImage();

        $productImage->setFile($uploadedFile);
        $productImage->setType("thumbnail");
        
        $this->uploader->upload($productImage);

        $product = new Product();

        $product->setFallbackLocale("fr_FR");
        $product->setCurrentLocale("fr_FR");

        $product->setCode($data['code']);
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setMetaDescription(mb_strimwidth($data['description'],0,150));
        $product->setEnabled(true);
        $product->setSlug(strtolower($data['code']));

        $product->addChannel($channel);
        $product->addProductTaxon($productTaxon);
        $product->addVariant($productVariant);
        $product->addImage($productImage);

        return $product;
    }

    public function getListOfProduct(): array
    {
        return [
            [
                "code" => "BAGUES",
                "name" => "Bague",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "BAGUE",
                "image" => "bague.jpg",
                "price" => 12000
            ],
            [
                "code" => "PETITE_BAGUE",
                "name" => "Petite bague diamant",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "BAGUE",
                "image" => "small-bague.jpg",
                "price" => 4000
            ],
            [
                "code" => "BAGUES_LUXE",
                "name" => "Bague Luxe",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "BAGUE",
                "image" => "bague-lux.jpg",
                "price" => 25000
            ],
            [
                "code" => "MONTRE",
                "name" => "Montre",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "MONTRE",
                "image" => "montre.jpg",
                "price" => 8000
            ],
            [
                "code" => "MONTRE_PENDENTIF_OR",
                "name" => "Montre ancienne dorée",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "MONTRE",
                "image" => "montre-or.jpg",
                "price" => 10000
            ],
            [
                "code" => "BRACELET",
                "name" => "Bracelet",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "BRACELET",
                "image" => "bracelet.jpg",
                "price" => 8000
            ],
            [
                "code" => "BRACELET_DIAMANT",
                "name" => "Bracelet diamant",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "BRACELET",
                "image" => "bracelet-diamant.jpg",
                "price" => 9000
            ],
            [
                "code" => "COLLIER",
                "name" => "Collier",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Praesent orci turpis, feugiat et quam at, mollis vulputate magna. Vestibulum diam velit, consectetur sit amet lorem a, imperdiet gravida felis. 
                    Curabitur dignissim erat eros, sit amet bibendum nulla semper ac. 
                    Mauris elementum elit in libero posuere imperdiet. Nulla elit sapien, facilisis at libero non, congue lobortis metus.",
                "taxon" => "COLLIER",
                "image" => "collier.jpg",
                "price" => 7000
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            ChannelsFixtures::class,
            TaxonFixtures::class,
            ShippingCategoriesFixtures::class,
            TaxCategoryFixtures::class
        ];
    }
}
