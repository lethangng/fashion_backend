<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'quantity',
        'extra_product',
    ];
}
