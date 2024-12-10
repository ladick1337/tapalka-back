<?php

namespace App\Models;

use Database\Factories\TaskHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoLinks extends Model
{
    use HasFactory;

    protected $table = 'videolinks';

    protected $fillable = [
        'client_id',
        'link',
        'watch',
        'status'
    ];

}
