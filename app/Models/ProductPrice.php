<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'product_prices';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'product_id',
        'price',
        'sell_off',
        'price_off',
        'is_select',
    ];
}
