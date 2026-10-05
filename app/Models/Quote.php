<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'user_id', 'reference', 'total_amount', 'subtotal',
        'tax_amount', 'discount_amount', 'status', 'valid_until', 'notes', 'payment_terms',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until && $this->valid_until->isPast() && $this->status === 'envoye';
    }
}