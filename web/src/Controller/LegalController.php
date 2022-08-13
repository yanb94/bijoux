<?php

namespace App\Controller;

use App\Entity\Legal;
use App\Repository\LegalRepository;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LegalController
{
    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    public function showAction(string $slug, LegalRepository $legalRepository): Response
    {
        $legal = $legalRepository->findOneBy(['slug' => $slug]);

        if(is_null($legal))
        {
            throw new NotFoundHttpException();
        }

        return $this->templatingEngine->renderResponse("@SyliusShop/Legal/show.html.twig",[
            "legal" => $legal
        ]);
    }

    public function listFooterAction(LegalRepository $legalRepository): Response
    {
        $legals = $legalRepository->findAll();

        return $this->templatingEngine->renderResponse("@SyliusShop/Legal/listFooter.html.twig",[
            "legals" => $legals
        ]);
    }

    public function listDrawerAction(LegalRepository $legalRepository): Response
    {
        $legals = $legalRepository->findAll();

        return $this->templatingEngine->renderResponse("@SyliusShop/Legal/listDrawer.html.twig",[
            "legals" => $legals
        ]);
    }
}