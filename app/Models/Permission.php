<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'group',
        'group_display_name',
        'sort_order',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permission');
    }

    public static function grouped()
    {
        return static::orderBy('sort_order')
            ->get()
            ->groupBy('group');
    }

    public static function getGroups()
    {
        return static::select('group', 'group_display_name')
            ->distinct()
            ->orderBy('sort_order')
            ->get()
            ->pluck('group_display_name', 'group');
    }
}
