<?php

namespace App\Controller;

use App\Form\ListProductFilterType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ListProductController extends AbstractController
{
    public function listAction(Request $request, ProductRepositoryInterface $productRepository, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(ListProductFilterType::class);

        $pagination = $params->get("nb_product_list");
        $page = 1;

        if($request->query->has('page'))
        {
            $page = $request->query->get('page') > 0 ? $request->query->get('page') : 1;
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            /** @var ArrayCollection */
            $categories = null;

            $priceMin = null;
            $priceMax = null;
            
            if(!empty($data['categories']))
            {
                $categories = $data['categories'];
                $taxonIds = array_map(function($object){
                    return intval($object->getId());
                },$categories->toArray());
            }

            if(!is_null($data['price_min']))
            {
                $priceMin = floatval($data['price_min']);
            }

            if(!is_null($data['price_max']))
            {
                $priceMax = floatval($data['price_max']);
            }

            $products = $productRepository->findAllByFilter($priceMin,$priceMax,$taxonIds,$pagination,$page);
            $count = $productRepository->countAllByFilter($priceMin,$priceMax,$taxonIds);
        }
        else
        {
            $products = $productRepository->findAllPaginated($pagination,$page);
            $count = $productRepository->countAll();
        }

        $maxPage = ceil($count/$pagination);

        $prevPage = ($page - 1) > 0 ? ($page - 1) : false;
        $nextPage = ($page + 1) <= $maxPage ? ($page + 1): false;

        return $this->render("@SyliusShop/ListProduct/index.html.twig",[
            "form" => $form->createView(),
            "products" => $products,
            "prevPage" => $prevPage,
            "nextPage" => $nextPage
        ]);
    }
}
