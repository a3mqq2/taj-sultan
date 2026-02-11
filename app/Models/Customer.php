<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(CustomerTransaction::class)->orderBy('created_at', 'desc');
    }

    public function specialOrders()
    {
        return $this->hasMany(SpecialOrder::class)->orderBy('created_at', 'desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function creditOrders()
    {
        return $this->orders()->where('credit_amount', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function getBalanceAttribute()
    {
        $payments = $this->transactions()
            ->whereIn('type', [CustomerTransaction::TYPE_PAYMENT, CustomerTransaction::TYPE_REFUND])
            ->sum('amount');

        $orders = $this->transactions()
            ->where('type', CustomerTransaction::TYPE_ORDER)
            ->sum('amount');

        return $payments - $orders;
    }

    public function calculateBalance()
    {
        return $this->balance;
    }

    public function addPayment($amount, $paymentMethodId, $userId, $notes = null)
    {
        DB::beginTransaction();
        try {
            $balanceBefore = $this->balance;
            $balanceAfter = $balanceBefore + $amount;

            $transaction = $this->transactions()->create([
                'type' => CustomerTransaction::TYPE_PAYMENT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'payment_method_id' => $paymentMethodId,
                'user_id' => $userId,
                'notes' => $notes,
            ]);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addOrderTransaction($amount, $specialOrderId, $userId)
    {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore - $amount;

        return $this->transactions()->create([
            'type' => CustomerTransaction::TYPE_ORDER,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => SpecialOrder::class,
            'reference_id' => $specialOrderId,
            'user_id' => $userId,
        ]);
    }

    public function addCreditOrderTransaction($creditAmount, $orderId, $userId)
    {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore - $creditAmount;

        return $this->transactions()->create([
            'type' => CustomerTransaction::TYPE_ORDER,
            'amount' => $creditAmount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => Order::class,
            'reference_id' => $orderId,
            'user_id' => $userId,
        ]);
    }

    public function canDelete()
    {
        return $this->transactions()->count() === 0
            && $this->specialOrders()->count() === 0
            && $this->orders()->count() === 0;
    }
}
