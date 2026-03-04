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
        "product_unique_id",
        "category_id"
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];


    protected static function boot(): void
       {
           parent::boot();

           // create a randon unique id
           static::creating(function ($product) {
                      if (empty($product->slug)) {
                          $product->product_unique_id = Str::orderedUuid();
                          $product->slug = Str::slug($product->name) . '-' . uniqid();
                      }
                  });
       }

    public function category()
    {
            return $this->belongsTo(Category::class);
    }

    // ── Route model binding uses slug ──
    public function getRouteKeyName(): string
    {
            return 'slug';
    }


    // ── Related products (same category, excluding self) ──
    public function related(int $limit = 4)
      {
          return static::where('category_id', $this->category_id)
              ->where('id', '!=', $this->id)
              ->inRandomOrder()
              ->limit($limit)
              ->get();
      }


}