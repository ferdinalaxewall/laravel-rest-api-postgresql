<?php

namespace App\Repositories\ProductOrder;

use App\Models\ProductOrder;
use App\Repositories\BaseRepository;

class ProductOrderRepositoryImplement extends BaseRepository implements ProductOrderRepository
{
    protected $model;

    public function __construct(ProductOrder $model)
    {
        parent::__construct($model);
    }

    public function findAllNotDeleted()
    {
        return $this->model->where('tgl_dihapus', null)->get();
    }

    public function findOneNotDeletedByOneSpecificColumn($columnName, $value)
    {
        return $this->model->where($columnName, $value)->where('tgl_dihapus', null)->first();
    }

    public function softDeleteProductOrder($pesanan_produk_id)
    {
        return $this->model->where('pesanan_produk_id', $pesanan_produk_id)->update(['tgl_dihapus' => now()]);
    }
}