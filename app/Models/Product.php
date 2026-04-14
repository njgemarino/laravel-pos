<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'category',
        'price',
        'stock',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}