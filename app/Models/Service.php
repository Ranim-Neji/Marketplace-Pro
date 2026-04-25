<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'availability',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'availability' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ─── Helpers ────────────────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return 'https://picsum.photos/seed/service-' . $this->id . '/800/600';
    }

    // ─── Auto-slug ───────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name) . '-' . uniqid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
