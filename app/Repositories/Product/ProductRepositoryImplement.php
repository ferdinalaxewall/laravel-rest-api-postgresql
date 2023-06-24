<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepositoryImplement extends BaseRepository implements ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findAllNotDeleted()
    {
        return $this->model->with(['orders', 'detail'])->where('tgl_dihapus', null)->get();
    }

    public function findOneNotDeletedByOneSpecificColumn($columnName, $value)
    {
        return $this->model->with(['orders', 'detail'])->where($columnName, $value)->where('tgl_dihapus', null)->first();
    }

    public function softDeleteProduct($produk_id)
    {
        return $this->model->where('produk_id', $produk_id)->update(['tgl_dihapus' => now()]);
    }
}