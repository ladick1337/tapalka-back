<?php

namespace App\Jobs;

use App\Consts\SendingStatuses;
use App\Models\Client;
use App\Models\Sending;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TelegramBot\Api\BotApi;

class SendingStart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sending;

    public function __construct(Sending $sending)
    {
        $this->sending = $sending;
    }

    protected function bot() : BotApi
    {
        return new BotApi(
            config('game.bot.token')
        );
    }

    protected function send(Client $client)
    {

        $this->bot()->sendMessage(
            $client->chat_id,
            $this->sending->text,
            'HTML',
            true
        );

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sending = $this->sending;

        $clients = Client::alive()
            ->where('lang', $sending->lang)
            ->lazyById(10);

        $i = 0;

        foreach($clients as $client){

            try{

                $this->send($client);

                if(!(++$i % 10)){

                    time_nanosleep(2, 0);

                    $sending->increment('users_complete', 10);
                    $sending->refresh();

                    if($sending->status === SendingStatuses::CANCELED){
                        break;
                    }

                }

            }catch (\Throwable $e){

            }

        }

        $sending->users_complete = $i;
        $sending->users_all = $i;
        $sending->status = SendingStatuses::COMPLETED;
        $sending->save();

    }

}
