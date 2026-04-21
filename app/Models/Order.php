<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'tax',
        'shipping', 'total', 'payment_method', 'payment_status',
        'shipping_address', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_VALIDATED => 'success',
            self::STATUS_SHIPPED => 'primary',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default      => 'secondary',
        };
    }
}
