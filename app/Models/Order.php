<?php

namespace App\Models;

use App\Models\Order;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $guarded = [];
    protected $connection = 'pgsql-Trx';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false; 
  
    // Set UUID v4 to Primary Key
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->pesanan_id = Uuid::uuid4()->toString();
        });
    }

    public function user()
    {
        return $this->hasOne('\App\Models\User', 'user_id', 'user_id');
    }

    public function productOrders()
    {
        return $this->hasMany('\App\Models\ProductOrder', 'pesanan_id', 'pesanan_id');
    }
}
