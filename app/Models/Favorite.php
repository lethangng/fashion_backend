<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorites';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'product_id',
        'user_id',
    ];
}
