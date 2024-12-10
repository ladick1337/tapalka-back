<?php

namespace App\Http\Controllers\Client;

use App\Consts\Languages;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Modules\Api\ApiError;
use App\Modules\Prepare\ClientPrepare;
use App\Modules\Traits\ClientGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    use ClientGuard;

    protected function prepareInitData(array $initDataArray) : string
    {

        $dataCheckString = [];
        foreach ($initDataArray as $key => $value) {
            if($key !== 'hash') {
                $dataCheckString[] = "{$key}={$value}";
            }
        }

        sort($dataCheckString);

        return implode("\n", $dataCheckString);

    }

    protected function parseInitData(string $initData) : array
    {

        $token = config('game.bot.token');

        parse_str($initData, $result);

        if(array_key_exists('hash', $result)){

            $hash = $result['hash'];
            $dataCheckString = $this->prepareInitData($result);

            $dataCheckHash = hash_hmac(
                'sha256',
                $dataCheckString,
                hash_hmac('sha256', $token, 'WebAppData',true)
            );

            if($hash !== $dataCheckHash){
                throw new \Error('Bad hash');
            }

            return $result;

        }else{
            throw new \Error('Bad hash');
        }

    }

    public function login(Request $request) : array
    {

        $data = $request->validate([
            'initData' => ['required', 'string']
        ]);

        try{

            $data = $this->parseInitData($data['initData']);
        }catch (\Throwable $e){
            throw new ApiError('Bad hash');
        }

        $user = json_decode($data['user'], true);

        $client = Client::where('chat_id', $user['id'])->first();

        if(!$client){

            $client = Client::factory()
                ->fromTelegram($user)
                ->setEnergyLimit(config('game.Energy.initialVolume'))
                ->setEnergyCharges(config('game.Energy.initialCharges'))
                ->maxEnergy()
                ->create();

        }

        $client->activity_at = now();
        $client->save();

        $token = $client->createToken($client->id)->plainTextToken;

        return [
            'token' => $token,
            'game' => [
                'energy' => [
                    'levelUps' => config('game.Energy.levelUps'),
                    'spendRate' => config('game.Energy.spendRate'),
                    'rewardRate' => config('game.Energy.rewardRate'),
                    'market' => config('game.Energy.market'),
                    'freeChargeBonusInterval' => config('game.Energy.freeChargeBonusInterval'),
                ],
                'friends' => [
					'reward' => config('game.Friends.reward'),
					'rewardPremium' => config('game.Friends.rewardPremium'),
					'inviteMessage' => config('game.Friends.inviteMessage'),
					'inviteLink' => 'https://t.me/' . config('game.bot.username') . '?start=' . $client->chat_id
				],
				'bot' => [
					'username' => config('game.bot.username')
				]
            ]
        ];

    }

    public function me(Request $request) : array
    {

        return ClientPrepare::client(
            $this->client()
        );

    }

    public function setLanguage(Request $request)
    {

        $data = $request->validate([
            'code' => ['required', 'string', Rule::in(array_keys(Languages::HINTS))],
        ]);

        $client = $this->client();

        $client->lang = $data['code'];
        $client->save();

    }

}
