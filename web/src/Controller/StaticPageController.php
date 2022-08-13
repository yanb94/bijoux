<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

final class StaticPageController
{
    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function aboutAction()
    {
        return $this->templatingEngine->renderResponse("@SyliusShop/StaticPage/about.html.twig");
    }
}