<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    use HasFactory;
    protected $table = 'evaluates';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'star_number',
        'content',
        'images',
        'status',
    ];
}
