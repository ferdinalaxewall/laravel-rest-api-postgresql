<?php

namespace App\Repositories\ProductStock;

use App\Models\StockProduct;
use App\Repositories\BaseRepository;

class ProductStockRepositoryImplement extends BaseRepository implements ProductStockRepository
{
    protected $model;

    public function __construct(StockProduct $model)
    {
        parent::__construct($model);
    }
}