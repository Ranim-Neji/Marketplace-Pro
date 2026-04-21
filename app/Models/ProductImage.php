<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/'.$this->image_path);
        }

        return 'https://picsum.photos/seed/product-image-'.$this->id.'/800/600';
    }
}
