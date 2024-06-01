<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    protected $table = 'order_products';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'extra_product',
    ];
}
