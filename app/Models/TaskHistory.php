<?php

namespace App\Models;

use Database\Factories\TaskHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskHistory extends Model
{
    use HasFactory;

    protected $table = 'tasks_history';

    protected $fillable = [
        'task_id',
        'client_id'
    ];

    static public function factory() : TaskHistoryFactory
    {
        return TaskHistoryFactory::new();
    }

    public function task() : BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

}
