<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'payment_intent_id',
        'card_last4',
        'card_brand',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_method',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'postcode',
        'country',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'shipping_cost'=> 'decimal:2',
        'total'        => 'decimal:2',
    ];

    // ── Relationships ──
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helpers ──
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->postcode,
            $this->country,
        ]));
    }

    public function getCardSummaryAttribute(): string
    {
        if (!$this->card_last4) return 'N/A';
        $brand = ucfirst($this->card_brand ?? 'Card');
        return $brand . ' •••• ' . $this->card_last4;
    }

    public function getStatusColourAttribute(): string
    {
        return match($this->status) {
            'pending'    => '#b8903a',
            'processing' => '#2563eb',
            'shipped'    => '#059669',
            'delivered'  => '#047857',
            'cancelled'  => '#dc2626',
            default      => '#7a7a72',
        };
    }
}