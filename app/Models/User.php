<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'password',
        'role',
        'is_active',
        'pos_point_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function posPoint(): BelongsTo
    {
        return $this->belongsTo(PosPoint::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    public function isSales()
    {
        return $this->role === 'sales';
    }

    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isCashier() || $this->isSales()) {
            return false;
        }

        return $this->permissions()->where('name', $permission)->exists();
    }

    public function hasAnyPermission(array $permissions)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isCashier() || $this->isSales()) {
            return false;
        }

        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }

        return $this;
    }

    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }

        return $this;
    }

    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
        return $this;
    }

    public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCashiers($query)
    {
        return $query->where('role', 'cashier');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeSales($query)
    {
        return $query->where('role', 'sales');
    }
}
