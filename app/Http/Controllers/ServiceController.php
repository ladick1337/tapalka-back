<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TelegramBot\Api\BotApi;

class ServiceController extends Controller
{

    protected function token() : string
    {
        return config('game.bot.token');
    }

    protected function bot() : BotApi
    {
        return new BotApi(
            $this->token()
        );
    }

    public function getSign() : string
    {
        return sha1($this->token());
    }

    public function sw(Request $request)
    {

        $this->bot()->setWebhook(
            'https://api.durov-tap.ru/services/webhook',
            null, null, 100, null, true,
            $this->getSign()
        );

    }

    protected function getOrCreateClient(array $chat) : Client
    {

        $client = Client::where('chat_id', $chat['id'])->first();

        if(!$client){
            $client = Client::factory()
                ->fromTelegram($chat)
                ->setEnergyCharges(config('game.Energy.initialCharges'))
                ->setEnergyLimit(config('game.Energy.initialVolume'))
                ->maxEnergy()
                ->create();
        }

        return $client;

    }

    protected function parseTelegramUpdate(array $update) : ?array
    {

        if(isset($update['pre_checkout_query'])){
            return [
                'method' => 'answerPreCheckoutQuery',
                'pre_checkout_query_id' => $update['pre_checkout_query']['id'],
                'ok' => true
            ];
        }


        if (isset($update['message']['text'])) {

            if($update['message']['from']['id'] !== $update['message']['chat']['id']) {
                return null;
            }

            $client = $this->getOrCreateClient($update['message']['from']);

            //Начисляем реф
            if(preg_match('/^\/start(.+)$/', $update['message']['text'], $parent)) {

                $parent = trim($parent[1]);
                $parent = Client::where('chat_id', $parent)->first();

                if($parent){

                    $reward = isset($update['message']['from']['is_premium'])
                        ? config('game.Friends.rewardPremium')
                        : config('game.Friends.reward');

                    DB::transaction(function() use ($reward, $parent, $client){

                        $client->restoreBalance($reward);
                        $client->parent_id = $client->id;
                        $client->save();

                        $parent->restoreBalance($reward);

                        $parent->increment('invited_friends');

                        if($parent->parent_id && $parent->parent->ref_percent > 0){

                            $parentReward = $reward * ($parent->parent->ref_percent / 100);
                            $parentReward = ceil($parentReward);

                            if($parentReward >= 1){
                                $parent->parent->restoreBalance($parentReward);
                            }

                        }

                    });

                }

            }

            return [
                'method' => 'sendMessage',
                'chat_id' => $client->chat_id,
                'text' => [
                    'ru' => implode("\n", [
                        '<b>👐 Добро пожаловать в Квест Дурова!</b>',
                        '',
                        'Присоединяйтесь к приключению, где вы помогаете основателю преодолевать препятствия на пути к свободе.🗽',
                        'Начните тапать, зарабатывайте награды и открывайте новые уровни свободы, силы и влияния.🎁',
                        'Каждый тап приближает основателя к освобождению.🔓',
                        '',
                        '<b>⏬Нажмите \'ИГРАТЬ\' и раскройте силу настойчивости!⏬</b>'
                    ]),
                    'en' => implode("\n", [
                        '<b>👐 Welcome to Durov\'s Quest!</b>',
                        '',
                        'Join the adventure where you help the founder overcome obstacles on his path to freedom. 🗽',
                        'Start tapping, earn rewards, and unlock new levels of freedom, power, and influence. 🎁',
                        'Each tap brings the founder closer to liberation. 🔓',
                        '',
                        '<b>⏬ Press \'PLAY\' and unleash the power of persistence! ⏬</b>'
                    ])
                ][$client->lang],
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'inline_keyboard' => [
                        'ru' =>  [
                            [['text' => '✅ПОДПИСАТЬСЯ НА КАНАЛ', 'url' => 'https://t.me/+AWRkYNxNUdUwMTQ0']],
                            [['text' => '❓КАК ИГРАТЬ', 'callback_data' => 'how_to_play']],
                            [['text' => '🕹ИГРАТЬ', 'web_app' => ['url' => 'https://durov-tap.ru']]],
                        ],
                        'en' =>  [
                            [['text' => '✅ SUBSCRIBE CHANNEL', 'url' => 'https://t.me/+AWRkYNxNUdUwMTQ0']],
                            [['text' => '❓ HOW TO PLAY', 'callback_data' => 'how_to_play']],
                            [['text' => '🕹 PLAY', 'web_app' => ['url' => 'https://durov-tap.ru']]],
                        ]
                    ][$client->lang]
                ]
            ];

        }

        if (isset($update['callback_query'])) {

            $callbackQuery = $update['callback_query'];
            $client = $this->getOrCreateClient($callbackQuery['message']['chat']);

            $this->bot()->sendMessage($client->chat_id, $callbackQuery['data']);

            if ($callbackQuery['data'] === 'how_to_play') {

                return [
                    'method' => 'sendMessage',
                    'chat_id' => $client->chat_id,
                    'text' => [
                        'ru' => implode("\n", [
                            '<b>📃О игре: "Free Founder" - это уникальная мини-игра в Telegram, где игроки помогают освободить известного основателя, тапая по экрану. Используя сценарии основанные на реальных событиях, игра объединяет пользователей в общей миссии по сбору тапов и привлечению новых участников, в поддержку Павла Дурова.</b>',
                            '',
                            '🎢<b>Уровни:</b> Игра включает в себя множество уровней, начиная с тюремного заключения основателя. Каждый новый уровень представляет уникальные вызовы и возможности для прокачки персонажа и его способностей влиять на мир вокруг себя.',
                            '',
                            '🚀<b>Прокачка:</b> Используйте тапы для улучшения четырех ключевых характеристик: Деньги, Сила, Защита и Свобода. Каждый тап увеличивает силу в одной из этих областей, повышая шансы на успешное завершение миссий и получение наград.',
                            '',
                            '🫂<b>Друзья:</b> Вы можете приглашать друзей для совместной игры, что не только увеличивает социальный аспект, но и дает обеим сторонам бонусы. Совместная игра увеличивает шансы на успех в борьбе за свободу и конфиденциальность, а также повышает динамику прокачки.',
                            '',
                            '🎯<b>Цели:</b> Набрать необходимое количество ресурсов для полного освобождения основателя и достижения высокого рейтинга среди всех игроков. Игра сочетает в себе элементы стратегии и случайности, отражающие динамику реального мира.',
                            '',
                            '🏆<b>Активность:</b> Активные игроки могут участвовать в различных заданиях и соревнованиях, публикуя свои достижения в социальных сетях или участвуя в благотворительных мероприятиях, что дает дополнительные бонусы и призы.',
                            '',
                            '🎁<b>Эирдроп:</b> Когда будет выпущен токен, он будет распределен между игроками.',
                            '',
                            '<i>Эта игра не только развлекает, но и способствует формированию сообщества, мотивированного на помощь и поддержку в реальной жизни. Вступайте в игру, чтобы помочь освободить основателя и стать частью глобального движения за свободу и справедливость!</i>'

                        ]),
                        'en' => implode("\n", [
                            '📃 About the game: "Free Founder" is a unique mini-game on Telegram, where players help free a well-known founder by tapping on the screen. Using scenarios based on real events, the game unites users in a common mission to collect taps and attract new participants in support of Pavel Durov.',
                            '',
                            '🎢 Levels: The game includes multiple levels, starting with the founder’s imprisonment. Each new level presents unique challenges and opportunities for upgrading the character and his abilities to influence the world around him.',
                            '',
                            '🚀 Upgrades: Use taps to improve four key characteristics: Money, Power, Protection, and Freedom. Each tap increases strength in one of these areas, enhancing the chances of successfully completing missions and earning rewards.',
                            '',
                            '🫂 Friends: You can invite friends to play together, which not only enhances the social aspect but also gives both parties bonuses. Cooperative play increases the chances of success in the fight for freedom and privacy, and also enhances the dynamics of upgrading.',
                            '',
                            '🎯 Goals: Accumulate the necessary resources to fully free the founder and achieve a high ranking among all players. The game combines elements of strategy and randomness, reflecting the dynamics of the real world.',
                            '',
                            '🏆 Activity: Active players can participate in various tasks and competitions, posting their achievements on social media or participating in charity events, which provides additional bonuses and prizes.',
                            '',
                            '🎁 Airdrop: When the token is released, it will be distributed among the players.',
                            '',
                            'This game not only entertains but also fosters a community motivated to help and support in real life. Join the game to help free the founder and become part of a global movement for freedom and justice!'

                        ])
                    ][$client->lang],
                    'parse_mode' => 'HTML'
                ];

            }
        }

        if (isset($update['my_chat_member'])) {

            if($update['my_chat_member']['from']['id'] !== $update['my_chat_member']['chat']['id']) {
                return null;
            }

            $client = $this->getOrCreateClient($update['my_chat_member']['from']);

            $status = $update['my_chat_member']['new_chat_member']['status'];

            if($status === 'left' || $status === 'kicked') {
                $client->is_alive = false;
                $client->save();
            }

            return null;

        }

        if (isset($update['message']['successful_payment'])) {

            $recharges = $update['message']['successful_payment']['invoice_payload'];

            $client = $this->getOrCreateClient($update['message']['from']);

            $client->restoreEnergyCharges($recharges);

            return null;

        }

        return null;

    }

    public function webhook(Request $request)
    {

        $hash = $request->header('x-telegram-bot-api-secret-token');

        if($hash !== $this->getSign()){
            return abort(403);
        }

        // Преобразуем данные в массив
        $update = json_decode( file_get_contents('php://input'), true);

        try {
            $json = $this->parseTelegramUpdate($update);

            if ($json) {
                return $json;
            }
        }catch (\Throwable $e){
//            $this->bot()->sendMessage(1968884969, $e->getMessage());
        }

    }

}
