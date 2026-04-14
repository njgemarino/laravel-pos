<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'receipt_no',
        'total_amount',
        'payment',
        'change_amount',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}