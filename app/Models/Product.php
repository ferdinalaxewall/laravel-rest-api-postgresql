<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $guarded = ['produk_id'];
    protected $connection = 'pgsql-Mst';

    protected $primaryKey = null;
    public $incrementing = false;
    
    const CREATED_AT = 'tgl_dibuat';
    const UPDATED_AT = 'tgl_diubah';

    // Set UUID v4 to Primary Key
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->produk_id = Uuid::uuid4()->toString();
        });
    }
    
    public function orders()
    {
        return $this->hasMany('\App\Models\ProductOrder', 'produk_id', 'produk_id');
    }

    public function detail()
    {
        return $this->hasOne('\App\Models\StockProduct', 'produk_id', 'produk_id');
    }
}
