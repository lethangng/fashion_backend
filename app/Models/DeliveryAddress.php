<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;
    protected $table = 'delivery_addresses';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'user_id',
        'city',
        'address',
        'is_select',
    ];
}
