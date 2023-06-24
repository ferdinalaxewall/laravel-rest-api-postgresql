<?php

namespace App\Services\ProductStock;

interface ProductStockService
{
    public function findAllProductStockData();
    public function findOneProductStockData($produk_id);
    public function createProductStockData($dto);
    public function updateOneProductStockData($produk_id, $dto);
    public function deleteOneProductStockData($produk_id);
}