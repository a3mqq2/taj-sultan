<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    const TYPE_PAYMENT = 'payment';
    const TYPE_ORDER = 'order';
    const TYPE_REFUND = 'refund';

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'payment_method_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public static function getTypes()
    {
        return [
            self::TYPE_PAYMENT => 'دفعة',
            self::TYPE_ORDER => 'طلب',
            self::TYPE_REFUND => 'استرجاع',
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

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function reference()
    {
        if ($this->reference_type && $this->reference_id) {
            return $this->reference_type::find($this->reference_id);
        }
        return null;
    }

    public function getTypeNameAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function getDescriptionAttribute()
    {
        if ($this->type === self::TYPE_ORDER && $this->reference_type === SpecialOrder::class) {
            return "طلب خاص #{$this->reference_id}";
        }

        if ($this->type === self::TYPE_PAYMENT) {
            $method = $this->paymentMethod ? " - {$this->paymentMethod->name}" : '';
            return "دفعة{$method}";
        }

        if ($this->type === self::TYPE_REFUND) {
            return 'استرجاع';
        }

        return $this->notes ?? '-';
    }

    public function getTypeBadgeClassAttribute()
    {
        return match($this->type) {
            self::TYPE_PAYMENT => 'badge-success',
            self::TYPE_ORDER => 'badge-warning',
            self::TYPE_REFUND => 'badge-info',
            default => 'badge-secondary',
        };
    }

    public function isCredit()
    {
        return in_array($this->type, [self::TYPE_PAYMENT, self::TYPE_REFUND]);
    }

    public function isDebit()
    {
        return $this->type === self::TYPE_ORDER;
    }
}
