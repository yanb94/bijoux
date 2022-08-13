<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Dedi\SyliusSEOPlugin\Entity\SEOContent;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\ReferenceableTrait;
use Sylius\Component\Product\Model\ProductTranslationInterface;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\ReferenceableInterface;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\RichSnippetSubjectInterface;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\RichSnippetProductSubjectTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ReferenceableInterface, RichSnippetSubjectInterface
{
    use ReferenceableTrait {
        getMetadataTitle as getBaseMetadataTitle;
        getMetadataDescription as getBaseMetadataDescription;
        getOpenGraphMetadataImage as getOpenGraphMetadataImage;
    }
    use RichSnippetProductSubjectTrait;

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }

    public function getMetadataTitle(): ?string
    {
        if (is_null($this->getReferenceableContent()->getMetadataTitle())) {
            return $this->getName();
        }

        return $this->getBaseMetadataTitle();
    }

    public function getMetadataDescription(): ?string
    {
        if (is_null($this->getReferenceableContent()->getMetadataDescription())) {
            
            if(is_null($this->getShortDescription()))
            {
                return $this->getDescription();
            }

            return $this->getShortDescription();
        }

        return $this->getBaseMetadataDescription();
    }

    public function getOpenGraphMetadataImage(): ?string
    {
        if(is_null($this->getReferenceableContent()->getOpenGraphMetadataImage()))
        {
            if($this->getImages()->count() == 0) return null;
            
            return $this->getImages()[0]->getPath();
        }

        return $this->getReferenceableContent()->getOpenGraphMetadataImage();
    }

    protected function createReferenceableContent(): ReferenceableInterface
    {
        return new SEOContent();
    }

    public function getRichSnippetSubjectParent(): ?self
    {
        return $this->getMainTaxon();
    }

    public function getRichSnippetSubjectType(): string
    {
        return 'product';
    }
}
