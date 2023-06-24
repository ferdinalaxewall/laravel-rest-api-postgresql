<?php

namespace App\Repositories\ProductOrder;

interface ProductOrderRepository
{
    public function findAllNotDeleted();
    public function findOneNotDeletedByOneSpecificColumn($columnName, $value);
    public function softDeleteProductOrder($pesanan_produk_id);
}