<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'special_order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total_price',
        'is_weight',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'is_weight' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });

        static::updating(function ($item) {
            if ($item->isDirty(['quantity', 'unit_price'])) {
                $item->total_price = $item->quantity * $item->unit_price;
            }
        });
    }

    public function specialOrder()
    {
        return $this->belongsTo(SpecialOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
