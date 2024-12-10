<?php

namespace App\Models;

use App\Consts\SendingStatuses;
use Database\Factories\SendingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sending extends Model
{
    use HasFactory;

    protected $fillable = [
        'lang',
        'status',
        'text',
        'users_complete',
        'users_all'
    ];


    protected $attributes = [
        'status' => SendingStatuses::ACTIVE,
        'users_complete' => 0
    ];

    static public function factory() : SendingFactory
    {
        return SendingFactory::new();
    }

}
