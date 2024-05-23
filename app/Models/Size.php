<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'size',
        'description',
    ];
}
