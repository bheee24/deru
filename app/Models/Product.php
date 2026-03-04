<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'summary',
        'image',
        "description",
        "product_unique_id"
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];


    protected static function boot(): void
       {
           parent::boot();

           // create a randon unique id
           static::creating(function ($product) {
               $product->product_unique_id = Str::orderedUuid();
           });
       }
}