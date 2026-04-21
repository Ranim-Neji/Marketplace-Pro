<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'parent_id', 'is_active', 'sort_order'
    ];

    protected $casts = ['is_active' => 'boolean'];

    // ─── Relationships ──────────────────────────────────────────
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // ─── Auto-slug generation ────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
