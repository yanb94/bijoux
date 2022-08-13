<?php 

namespace App\Sitemap;

use App\Repository\LegalRepository;
use SitemapPlugin\Model\ChangeFrequency;
use SitemapPlugin\Provider\UrlProviderInterface;
use SitemapPlugin\Factory\SitemapUrlFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomSitemapProvider implements UrlProviderInterface
{
    private LegalRepository $legalRepository;
    private UrlGeneratorInterface $router;
    private SitemapUrlFactoryInterface $urlFactory;

    public function __construct(LegalRepository $legalRepository, UrlGeneratorInterface $router, SitemapUrlFactoryInterface $urlFactory)
    {
        $this->legalRepository = $legalRepository;
        $this->router = $router;
        $this->urlFactory = $urlFactory;
    }

    public function generate(): iterable
    {
        $urls = [];

        // Add Legal document
        $legals = $this->legalRepository->findAll();
        
        foreach ($legals as $legal) {

            $link = $this->router->generate('app_legal_show',['slug' => $legal->getSlug()],UrlGeneratorInterface::ABSOLUTE_URL);

            $url = $this->urlFactory->createNew();
            $url->setPriority(0.1);
            $url->setLocalization($link);
            $url->setChangeFrequency(ChangeFrequency::weekly());

            $urls[] = $url; 
        }

        // Add list product
        $link = $this->router->generate('list_product');

        $url = $this->urlFactory->createNew();
        $url->setPriority(0.5);
        $url->setLocalization($link);
        $url->setChangeFrequency(ChangeFrequency::daily());

        $urls[] = $url;

        // Add About page
        $link = $this->router->generate('app_static_page_about');

        $url = $this->urlFactory->createNew();
        $url->setPriority(0.4);
        $url->setLocalization($link);
        $url->setChangeFrequency(ChangeFrequency::monthly());

        $urls[] = $url;

        return $urls;
    }

    public function getName(): string
    {
        return "customs";
    }
}