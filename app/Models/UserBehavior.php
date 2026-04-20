<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBehavior extends Model
{
    protected $fillable = ['user_id', 'product_id', 'action', 'score'];

    // Score weights for recommendation engine
    const SCORES = [
        'view'     => 1,
        'wishlist' => 2,
        'cart'     => 3,
        'purchase' => 5,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
