<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tag',
        'cover_image',
        'description',
        'sort_order',
    ];

    // ── Auto-generate slug from name ──
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });


        static::creating(function ($category) {
            $category->category_unique_id = Str::orderedUuid();
        });
    }

    // ── Relationships ──
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ── Scopes ──
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }


}