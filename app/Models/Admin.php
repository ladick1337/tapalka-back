<?php

namespace App\Models;

use App\Modules\Traits\HasTwoFactoryCode;
use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasTwoFactoryCode, Notifiable, HasApiTokens;

    protected $fillable = [
        'login',
        'password',
        'role_id',
        'tfa_secret'
    ];

    protected $hidden = [
        'password',
        'tfa_secret'
    ];

    protected $appends = [
        'tfa_enabled'
    ];

    static public function factory(...$props) : AdminFactory
    {
        return AdminFactory::new(...$props);
    }

    static public function findByLogin(string $login) : ?self
    {
        return self::where('login', $login)->first();
    }

    public function getTfaEnabledAttribute() : bool
    {
        return !!$this->tfa_secret;
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function authHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminAuthHistory::class);
    }

    public function hasPermission(string $permission) : bool
    {
        return $this->role->hasPermission($permission);
    }

}
