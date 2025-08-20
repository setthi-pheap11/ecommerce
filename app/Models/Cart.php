<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total amount of the cart
     */
    public function getTotalAmount()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Get the total items count
     */
    public function getTotalItems()
    {
        return $this->items->sum('quantity');
    }
}
