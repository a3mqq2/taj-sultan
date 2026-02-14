<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'pos_point_id',
        'user_id',
        'customer_id',
        'total',
        'credit_amount',
        'discount',
        'delivery_type',
        'delivery_phone',
        'status',
        'merged_into',
        'paid_at',
        'paid_by',
        'payment_method_id',
        'amount_received',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:3',
            'credit_amount' => 'decimal:3',
            'discount' => 'decimal:3',
            'amount_received' => 'decimal:3',
            'paid_at' => 'datetime',
        ];
    }

    public function posPoint(): BelongsTo
    {
        return $this->belongsTo(PosPoint::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function mergedIntoOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'merged_into');
    }

    public function mergeRecordsAsParent(): HasMany
    {
        return $this->hasMany(OrderMerge::class, 'parent_order_id');
    }

    public function mergeRecordAsChild(): BelongsTo
    {
        return $this->belongsTo(OrderMerge::class, 'id', 'child_order_id');
    }

    public function mergedOrders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Order::class,
            OrderMerge::class,
            'parent_order_id',
            'id',
            'id',
            'child_order_id'
        );
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeHasCredit($query)
    {
        return $query->where('credit_amount', '>', 0);
    }

    public function scopeNotMerged($query)
    {
        return $query->whereNull('merged_into');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isMerged(): bool
    {
        return $this->merged_into !== null;
    }

    public function hasCredit(): bool
    {
        return $this->credit_amount > 0;
    }

    public function markAsPaid(int $paidBy): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'paid_by' => $paidBy,
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? ((int) substr($lastOrder->order_number, -4)) + 1 : 1;

        return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getChangeAttribute(): float
    {
        if (!$this->amount_received) {
            return 0;
        }
        return max(0, $this->amount_received - $this->total);
    }
}
