<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'total_price',
        'delivery_address',
        'user_id',
        'price_off',
        // 'order_date',
        // 'delivery_date',
        'status',
    ];
}
