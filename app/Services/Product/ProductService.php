<?php

namespace App\Services\Product;

interface ProductService
{
    public function findAllProductData();
    public function findOneProductData($produk_id);
    public function createProductData($dto);
    public function updateOneProductData($produk_id, $dto);
    public function deleteOneProductData($produk_id);
}