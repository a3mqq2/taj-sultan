<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'category_id',
        'pos_point_id',
        'type',
        'barcode',
        'description',
        'is_active',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock' => 'decimal:3',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function posPoint(): BelongsTo
    {
        return $this->belongsTo(PosPoint::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderBy('created_at', 'desc');
    }

    public function addStock($quantity, $userId, $notes = null)
    {
        $stockBefore = $this->stock;
        $stockAfter = $stockBefore + $quantity;

        $this->update(['stock' => $stockAfter]);

        return $this->stockMovements()->create([
            'type' => StockMovement::TYPE_ADDITION,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'user_id' => $userId,
            'notes' => $notes,
        ]);
    }

    public function deductStock($quantity, $orderId, $userId = null)
    {
        $stockBefore = $this->stock;
        $stockAfter = $stockBefore - $quantity;

        $this->update(['stock' => $stockAfter]);

        return $this->stockMovements()->create([
            'type' => StockMovement::TYPE_SALE,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => Order::class,
            'reference_id' => $orderId,
            'user_id' => $userId,
        ]);
    }

    public function manualDeductStock($quantity, $userId, $notes = null)
    {
        $stockBefore = $this->stock;
        $stockAfter = $stockBefore - $quantity;

        $this->update(['stock' => $stockAfter]);

        return $this->stockMovements()->create([
            'type' => StockMovement::TYPE_ADJUSTMENT,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'user_id' => $userId,
            'notes' => $notes,
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeForPosPoint($query, $posPointId)
    {
        return $query->where('pos_point_id', $posPointId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%");
        });
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' د.ل';
    }

    public function getTypeNameAttribute()
    {
        return $this->type === 'piece' ? 'قطعة' : 'وزن';
    }

    public function getStatusNameAttribute()
    {
        return $this->is_active ? 'مفعل' : 'موقوف';
    }
}
