<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SpecialOrder extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_READY = 'ready';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'phone',
        'event_type',
        'description',
        'delivery_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->remaining_amount = $order->total_amount - ($order->paid_amount ?? 0);
        });

        static::updating(function ($order) {
            if ($order->isDirty('total_amount')) {
                $order->remaining_amount = $order->total_amount - $order->paid_amount;
            }
        });
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'معلق',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_READY => 'جاهز',
            self::STATUS_DELIVERED => 'تم التسليم',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function getEventTypes()
    {
        return [
            'birthday' => 'عيد ميلاد',
            'wedding' => 'زفاف',
            'engagement' => 'خطوبة',
            'graduation' => 'تخرج',
            'eid' => 'عيد',
            'other' => 'أخرى',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(SpecialOrderPayment::class)->orderBy('created_at', 'desc');
    }

    public function items()
    {
        return $this->hasMany(SpecialOrderItem::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDeliveryBetween($query, $from, $to)
    {
        return $query->whereBetween('delivery_date', [$from, $to]);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('customer_name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhereHas('customer', function ($q2) use ($term) {
                  $q2->where('name', 'like', "%{$term}%")
                     ->orWhere('phone', 'like', "%{$term}%");
              });
        });
    }

    public function updateAmounts()
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    public function calculateTotalFromItems()
    {
        return $this->items()->sum('total_price');
    }

    public function syncItemsTotal()
    {
        $this->total_amount = $this->calculateTotalFromItems();
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    public function addPayment($amount, $paymentMethodId, $userId, $notes = null)
    {
        DB::beginTransaction();
        try {
            $payment = $this->payments()->create([
                'payment_method_id' => $paymentMethodId,
                'amount' => $amount,
                'user_id' => $userId,
                'notes' => $notes,
            ]);

            if ($this->customer) {
                $balanceBefore = $this->customer->balance;
                $balanceAfter = $balanceBefore + $amount;

                CustomerTransaction::create([
                    'customer_id' => $this->customer_id,
                    'type' => CustomerTransaction::TYPE_PAYMENT,
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'reference_type' => self::class,
                    'reference_id' => $this->id,
                    'payment_method_id' => $paymentMethodId,
                    'user_id' => $userId,
                    'notes' => "دفعة طلب خاص #{$this->id}" . ($notes ? " - {$notes}" : ''),
                ]);
            }

            $this->updateAmounts();

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function isPaid()
    {
        return $this->remaining_amount <= 0;
    }

    public function canDelete()
    {
        return $this->payments()->count() === 0;
    }

    public function getDisplayNameAttribute()
    {
        if ($this->customer) {
            return $this->customer->name;
        }
        return $this->customer_name;
    }

    public function getDisplayPhoneAttribute()
    {
        if ($this->customer) {
            return $this->customer->phone;
        }
        return $this->phone;
    }

    public function getStatusNameAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getEventTypeNameAttribute()
    {
        return self::getEventTypes()[$this->event_type] ?? $this->event_type;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_IN_PROGRESS => 'badge-info',
            self::STATUS_READY => 'badge-primary',
            self::STATUS_DELIVERED => 'badge-success',
            self::STATUS_CANCELLED => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
