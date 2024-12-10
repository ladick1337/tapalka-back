<?php

namespace App\Models;

use App\Consts\Languages;
use App\Exceptions\Client\NoEnergyChargesException;
use App\Exceptions\Client\NoEnergyException;
use App\Exceptions\Client\NoFundsException;
use Carbon\Carbon;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'chat_id',
        'name',
        'username',
        'energy',
        'energy_charges',
        'energy_level',
        'energy_max',
        'balance',
        'parent_id',
        'lang',
        'invited_friends',
        'energy_bonus_at',
        'activity_at',
        'is_alive',
        'ref_percent',
        'energy_wasted_at'
    ];

    protected $casts = [
        'energy_bonus_at' => 'datetime',
        'activity_at' => 'datetime',
        'energy_wasted_at' => 'datetime'
    ];

    protected $attributes = [
        'ref_percent' => 0,
        'energy_level' => 1,
        'energy_charges' => 0,
        'balance' => 0,
        'lang' => Languages::EN,
        'invited_friends' => 0,
        'is_alive' => true
    ];

    protected $appends = [
        'energy_bonus_available',
        'energy_auto_recharge_time'
    ];

    static public function factory(...$parameters) : ClientFactory
    {
        return ClientFactory::new(...$parameters);
    }

    public function scopeAlive($query)
    {
        return $query->where('is_alive', true);
    }

    public function getEnergyAutoRechargeTimeAttribute() : ?int
    {

        if($this->energy_wasted_at){

            $target = $this->energy_wasted_at->addSeconds(
                config('game.Energy.uptime.interval')
            );

            return $target->diffInSeconds(now());

        }

        return null;

    }

    public function getEnergyBonusAvailableAttribute() : bool
    {
        return !$this->energy_bonus_at || $this->energy_bonus_at->addSeconds(config('game.Energy.freeChargeBonusInterval'))->isPast();
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function completedTasksHistory() : HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }


    public function completedTasks() : HasManyThrough
    {
        return $this->hasManyThrough(Task::class, TaskHistory::class, 'client_id', 'id', 'id', 'task_id');
    }

    public function spendEnergyCharges(int $count)
	{

        $rows = self::query()
            ->where('id', $this->id)
            ->where('energy_charges', '>=', $count)
            ->decrement('energy_charges', $count);

        if(!$rows){
            throw new NoEnergyChargesException;
        }

        $this->energy_charges -= $count;
        $this->syncOriginalAttribute('energy_charges');

	}

    public function restoreEnergyCharges(int $count)
    {
        $this->increment('energy_charges', $count);
    }

    public function restoreEnergy(int $value){
        $this->update([
            'energy' => DB::raw('LEAST(energy + ' . $value . ', energy_max)')
        ]);
    }

    public function spendEnergy(int $value)
    {

        $rows = self::query()
            ->where('id', $this->id)
            ->where('energy', '>=', $value)
            ->decrement('energy', $value);

        if(!$rows){
            throw new NoEnergyException;
        }

        $this->energy -= $value;
        $this->syncOriginalAttribute('energy');

    }

    public function restoreBalance(int $value){
        $this->increment('balance', $value);
    }

    public function spendBalance(int $value){

        $rows = self::query()
            ->where('id', $this->id)
            ->where('balance', '>=', $value)
            ->decrement('balance', $value);

        if(!$rows){
            throw new NoFundsException;
        }

        $this->balance -= $value;
        $this->syncOriginalAttribute('balance');

    }


}
