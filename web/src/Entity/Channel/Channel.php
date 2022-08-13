<?php

declare(strict_types=1);

namespace App\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;
use Dedi\SyliusSEOPlugin\Entity\SEOContent;
use Sylius\Component\Core\Model\Channel as BaseChannel;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\ReferenceableTrait;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\SeoAwareChannelTrait;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\ReferenceableInterface;
use Dedi\SyliusSEOPlugin\Domain\SEO\Adapter\SeoAwareChannelInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements ReferenceableInterface, SeoAwareChannelInterface
{
    use ReferenceableTrait;
    use SeoAwareChannelTrait;

    protected function createReferenceableContent(): ReferenceableInterface
    {
        return new SEOContent();
    }
}
