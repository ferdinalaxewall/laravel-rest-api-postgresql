<?php

namespace App\Repositories\Order;

interface OrderRepository
{
    public function findAllWithFullRelation();
    public function findOneByOneSpecificColumnWithFullRelation($columnName, $value);
}