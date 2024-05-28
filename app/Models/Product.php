<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'name',
        'category_id',
        'brand_id',
        'status',
        'newest',
        'sell_off',
        'image',
        'price_off',
        'list_image',
        'colors',
        'sizes',
        'import_price',
        'description',
    ];
}
