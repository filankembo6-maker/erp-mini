<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'quote_id', 'user_id', 'reference', 'total_amount',
        'subtotal', 'tax_amount', 'discount_amount', 'paid_amount',
        'status', 'due_date', 'notes', 'is_locked',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_locked' => 'boolean',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date->isPast()
            && !in_array($this->status, ['payee', 'annulee']);
    }
}