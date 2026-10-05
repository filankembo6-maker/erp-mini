<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'email', 'phone', 'address', 'city',
        'credit_limit', 'notes', 'segment',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalInvoicedAttribute()
    {
        return $this->invoices()->where('status', '!=', 'annulee')->sum('total_amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->invoices()->sum('paid_amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_invoiced - $this->total_paid;
    }
}