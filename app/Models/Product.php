<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'supplier_id', 'name', 'sku', 'barcode', 'description', 'photo',
        'price', 'purchase_price', 'stock_quantity', 'stock_alert', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getMarginAttribute()
    {
        if ($this->purchase_price <= 0) return 0;
        return round((($this->price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity === 0) return 'rupture';
        if ($this->stock_quantity <= $this->stock_alert) return 'bas';
        return 'ok';
    }
}