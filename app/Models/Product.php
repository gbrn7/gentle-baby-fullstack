<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'hpp',
        'price',
        'size_volume',
        'thumbnail',
        'is_cashback',
        'cashback_value',
        'status',
    ];
}
