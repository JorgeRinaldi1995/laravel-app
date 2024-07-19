<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_unit',
        'basic_unit',
        'tax_percentage',
        'limited',
        'active_for_sale',
    ];
}