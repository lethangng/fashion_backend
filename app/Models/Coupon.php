<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'name',
        'code',
        'price',
        'for_sum',
        'coupont_type',
        'expired',
        'description',
    ];
}
