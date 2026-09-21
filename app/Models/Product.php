<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'price', 'stock_quantity', 'stock_alert'];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
