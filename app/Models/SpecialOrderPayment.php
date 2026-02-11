<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'special_order_id',
        'payment_method_id',
        'amount',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function specialOrder()
    {
        return $this->belongsTo(SpecialOrder::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
