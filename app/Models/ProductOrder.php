<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\ProductOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'pesanan_produk';
    protected $guarded = ['pesanan_produk_id'];
    protected $connection = 'pgsql-Trx';

    protected $primaryKey = null;
    public $incrementing = false;
    
    const CREATED_AT = 'tgl_dibuat';
    const UPDATED_AT = 'tgl_diubah';

    // Set UUID v4 to Primary Key
    protected static function booted(): void
    {
        static::creating(function (ProductOrder $productOrder) {
            $productOrder->pesanan_produk_id = Uuid::uuid4()->toString();
        });
    }

    public function product()
    {
        return $this->hasOne('\App\Models\Product', 'produk_id', 'produk_id');
    }
}
