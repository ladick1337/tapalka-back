<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
        'name',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    protected $attributes = [
        'permissions' => '[]'
    ];

    public function admins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }

}
