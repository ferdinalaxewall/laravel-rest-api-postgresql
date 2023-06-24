<?php

namespace App\Repositories\Product;

interface ProductRepository
{
    public function findAllNotDeleted();
    public function findOneNotDeletedByOneSpecificColumn($columnName, $value);
    public function softDeleteProduct($produk_id);
}