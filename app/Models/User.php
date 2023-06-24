<?php

namespace App\Models;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Override Default Config for Connection, Table, and Column
    protected $table = 'user';
    protected $connection = 'pgsql-Usr';
    protected $guarded = ['user_id'];

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false; 

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->user_id = Uuid::uuid4()->toString();
        });
    }

}
