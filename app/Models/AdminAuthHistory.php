<?php

namespace App\Models;

use Database\Factories\AdminAuthHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAuthHistory extends Model
{

    use HasFactory;

    protected $fillable = [
        'admin_id',
        'ip'
    ];

    protected $hidden = [
        'ip'
    ];

    static public function factory(...$args) : AdminAuthHistoryFactory
    {
        return AdminAuthHistoryFactory::new(...$args);
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

}
