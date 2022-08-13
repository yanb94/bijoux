<?php

namespace App\Repository;

use Sylius\Component\Core\Repository\ProductRepositoryInterface as BaseProductRepositoryInterface;

interface ProductRepositoryInterface extends BaseProductRepositoryInterface
{
    public function findAllByFilter(?int $priceMin, ?int $priceMax, array $categories, int $pagination, int $page = 1): array;

    public function countAllByFilter(?int $priceMin, ?int $priceMax, array $categories): int;

    public function countAll():int;

    public function findAllPaginated(int $pagination, int $page = 1): array;
}