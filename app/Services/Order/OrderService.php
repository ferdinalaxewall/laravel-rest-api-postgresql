<?php

namespace App\Services\Order;

interface OrderService
{
    public function findAllOrderData();
    public function findOneOrderData($pesanan_id);
    public function createOrderData($dto);
    public function updateOneOrderData($pesanan_id, $dto);
    public function deleteOneOrderData($pesanan_id);
}