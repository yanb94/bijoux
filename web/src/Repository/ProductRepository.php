<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository implements ProductRepositoryInterface
{

    private function applyFilter(QueryBuilder $query,?int $priceMin, ?int $priceMax, array $categories): QueryBuilder
    {
        if(is_null($priceMin))
        {
            $priceMin = 0;
        }

        $query = $query->where("channelPricings.price > :priceMin")
        ->setParameter("priceMin",($priceMin * 100));

        if(!is_null($priceMax))
        {
            $query = $query->andWhere("channelPricings.price < :priceMax")
                ->setParameter("priceMax",($priceMax * 100));
        }

        if(!empty($categories))
        {
            $query = $query->andWhere(
                'taxon.id IN ('.implode(",",$categories).')'
            );
        }

        return $query;
    }

    private function paginate(QueryBuilder $query,int $pagination, int $page): QueryBuilder
    {
        return $query
            ->setFirstResult($pagination * ($page - 1))
            ->setMaxResults($pagination)
        ;
    }

    public function findAllByFilter(?int $priceMin, ?int $priceMax, array $categories, int $pagination, int $page = 1):array
    {
        $query = $this->createQueryBuilder("o")
            ->addSelect('variant')
            ->addSelect('translation')
            ->leftJoin('o.variants', 'variant')
            ->leftJoin('o.translations', 'translation')
            ->leftJoin('variant.channelPricings','channelPricings')
            ->leftJoin('o.productTaxons', 'productTaxons')
            ->leftJoin('productTaxons.taxon', 'taxon')
            ->orderBy("o.id")
        ;

        $query = $this->applyFilter($query,$priceMin,$priceMax,$categories);

        $query = $this->paginate($query,$pagination,$page);

        return $query->getQuery()->getResult();
    }

    public function countAllByFilter(?int $priceMin, ?int $priceMax, array $categories): int
    {
        $query = $this->createQueryBuilder("o")
            ->select("count(o.id)")
            ->leftJoin('o.variants', 'variant')
            ->leftJoin('variant.channelPricings','channelPricings')
            ->leftJoin('o.productTaxons', 'productTaxons')
            ->leftJoin('productTaxons.taxon', 'taxon')
        ;

        $query = $this->applyFilter($query,$priceMin,$priceMax,$categories);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function countAll(): int
    {
        $query = $this->createQueryBuilder("o")
            ->select("count(o.id)")
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

    public function findAllPaginated(int $pagination, int $page = 1): array
    {
        $query = $this->createQueryBuilder("o")
            ->addSelect('variant')
            ->addSelect('translation')
            ->leftJoin('o.variants', 'variant')
            ->leftJoin('o.translations', 'translation')
            ->leftJoin('variant.channelPricings','channelPricings')
            ->leftJoin('o.productTaxons', 'productTaxons')
            ->leftJoin('productTaxons.taxon', 'taxon')
        ;

        $query = $this->paginate($query,$pagination,$page);

        return $query->getQuery()->getResult();
    }
}