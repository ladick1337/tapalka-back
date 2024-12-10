<?php

namespace App\Models;

use App\Consts\Languages;
use App\Consts\TasksTypes;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use TelegramBot\Api\BotApi;

class Task extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'picture',
        'type',
        'lang',
        'url',
        'reward',
        'timeout',
        'telegram_channel_id',
        'complete_count',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'lang' => Languages::EN,
        'complete_count' => 0,
        'is_active' => true
    ];

    static public function factory() : TaskFactory
    {
        return TaskFactory::new();
    }

    public function check(Client $client) : bool
    {

        if($this->type === TasksTypes::TELEGRAM){

            $bot = new BotApi(
                config('game.bot.token')
            );

            try {

                $u = $bot->getChatMember($this->telegram_channel_id, $client->chat_id);

                if ($u->getStatus() !== 'left' && $u->getStatus() !== 'kicked') {
                    return true;
                }

            }catch (\Throwable $e){
                return false;
            }

        }else{
            return true;
        }

    }

    public function history() : HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function clients() : HasManyThrough
    {
        return $this->hasManyThrough(Client::class, TaskHistory::class, 'task_id', 'id', 'id', 'client_id');
    }

}
