<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'user_id',
        'cashier_name',
        'total_items',
        'subtotal',
        'discount',
        'total',
        'amount_paid',
        'change_due',
        'payment_method',
        'note',
        'receipt_printed',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'float',
            'discount' => 'float',
            'total' => 'float',
            'amount_paid' => 'float',
            'change_due' => 'float',
            'receipt_printed' => 'boolean',
            'sold_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
