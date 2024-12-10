<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\Client\NoEnergyChargesException;
use App\Exceptions\Client\NoEnergyException;
use App\Exceptions\Client\NoFundsException;
use App\Http\Controllers\Controller;
use App\Modules\Api\ApiError;
use App\Modules\Traits\ClientGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{

    use ClientGuard;

    public function tap(Request $request)
    {

        $data = $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:99999']
        ]);

        $client = $this->client();
        $count = $data['count'];

        try{

            $spendRate = config('game.Energy.spendRate');
			$rewardRate = config('game.Energy.rewardRate');

            DB::transaction(function() use ($spendRate, $rewardRate, $client, $count){

                if($client->parent_id && $client->parent->ref_percent > 0){

                    $parentReward = ($rewardRate * $count) * ($client->parent->ref_percent / 100);
                    $parentReward = ceil($parentReward);

                    if($parentReward >= 1){
                        $client->parent->restoreBalance($parentReward);
                    }

                }

                $client->spendEnergy($spendRate * $count);
                $client->restoreBalance($rewardRate * $count);

                if(!$client->energy){
                    $client->energy_wasted_at = now();
                    $client->save();
                }

            });

        }catch (NoEnergyException $e){
            throw new ApiError('Недостаточно энергии');
        }

    }

    public function energyRecharge()
    {

        $client = $this->client();

        try {
            DB::transaction(function () use ($client) {
                $client->spendEnergyCharges(1);
                $client->restoreEnergy($client->energy_max);
            });
        }catch (NoEnergyChargesException $e){
            throw new ApiError('Вы не можете восполнить энергию!');
        }

    }

    public function energyBonusRecharge(Request $request)
    {

        $client = $this->client();

        if($client->energy_bonus_available){

            DB::transaction(function() use ($client){

                $client->restoreEnergy($client->energy_max);

                $client->energy_bonus_at = now();
                $client->save();

            });

        }else{
            throw new ApiError('Бонус вам недоступен!');
        }

    }

    public function energyLevelUp(Request $request)
    {

        $client = $this->client();

        $level = $client->energy_level;
        $levels = config('game.Energy.levelUps');

        if(count($levels) < $level){
            throw new ApiError('Невозможно повысить уровень, вы достигли максимума');
        }

        $next = $levels[$level - 1];

        try {
            DB::transaction(function () use ($level, $client, $levels, $next) {

                $client->spendBalance($next['cost']);

                $client->update([
                    'energy_level' => $level + 1,
                    'energy_max' => $next['volume'],
                    'energy' => $next['volume']
                ]);

            });

        }catch (NoFundsException $e){
            throw new ApiError('Недостаточно средств');
        }

    }

}
