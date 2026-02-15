<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosPoint extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'active',
        'require_login',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'require_login' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function requiresLogin(): bool
    {
        return $this->require_login;
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }
}
