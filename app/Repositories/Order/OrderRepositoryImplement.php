<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;

class OrderRepositoryImplement extends BaseRepository implements OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function findAllWithFullRelation()
    {
        return $this->model->with(['user', 'productOrders'])->get();
    }

    public function findOneByOneSpecificColumnWithFullRelation($columnName, $value)
    {
        return $this->model->with(['user', 'productOrders'])->where($columnName, $value)->first();
    }
}