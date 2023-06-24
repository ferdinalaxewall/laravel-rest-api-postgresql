<?php

namespace App\Services\ProductOrder;

interface ProductOrderService
{
    public function findAllProductOrderData();
    public function findOneProductOrderData($pesanan_produk_id);
    public function createProductOrderData($dto);
    public function updateOneProductOrderData($pesanan_produk_id, $dto);
    public function deleteOneProductOrderData($pesanan_produk_id);
}