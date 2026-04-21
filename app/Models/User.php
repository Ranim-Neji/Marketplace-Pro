<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'bio', 'phone',
        'address', 'is_vendor', 'shop_name', 'shop_description', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_vendor' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ──────────────────────────────────────────
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function vendorReviews()
    {
        return $this->hasMany(Review::class, 'vendor_id')->where('is_approved', true);
    }

    public function getAverageVendorRatingAttribute(): float
    {
        return $this->vendorReviews()->avg('rating') ?? 0.0;
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function behaviors()
    {
        return $this->hasMany(UserBehavior::class);
    }

    public function conversationsAsFirst()
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function conversationsAsSecond()
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }

    public function conversations(): Builder
    {
        return Conversation::query()
            ->where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    // ─── Helpers ────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isVendor(): bool
    {
        return $this->hasRole('vendor') || $this->is_vendor;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    public function unreadMessagesCount(): int
    {
        return Message::whereHas('conversation', function ($query) {
            $query->where('user_one_id', $this->id)
                ->orWhere('user_two_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->whereNull('read_at')
        ->count();
    }
}
