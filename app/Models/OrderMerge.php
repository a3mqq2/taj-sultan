<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMerge extends Model
{
    protected $fillable = [
        'parent_order_id',
        'child_order_id',
        'merged_by',
    ];

    public function parentOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'parent_order_id');
    }

    public function childOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'child_order_id');
    }

    public function mergedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merged_by');
    }
}
