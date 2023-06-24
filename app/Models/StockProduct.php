<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockProduct extends Model
{
    use HasFactory;

    protected $table = 'produk_stok';
    protected $guarded = [];
    protected $connection = 'pgsql-Mst';
    
    protected $primaryKey = null;
    public $incrementing = false;
    
    const CREATED_AT = null;
    const UPDATED_AT = 'tgl_diubah';
}
