<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'energy_bonus_at' => now()->subDays(100)
        ];
    }

    protected function parseTelegramName(array $data) : string
    {

        $name = [];

        if(array_key_exists('first_name', $data)){
            $name[] = $data['first_name'];
        }

        if(array_key_exists('last_name', $data)){
            $name[] = $data['last_name'];
        }

        return implode(' ', $name);

    }

    protected function parseTelegramUsername(array $data) : ?string
    {
        return array_key_exists('username', $data) ? $data['username'] : null;
    }

    public function fromTelegram(array $data) : self
    {

        return $this->state([
            'chat_id' => $data['id'],
            'lang' => $data['language_code'] === 'ru' ? 'ru' : 'en',
            'username' => $this->parseTelegramUsername($data),
            'name' => $this->parseTelegramName($data)
        ]);

    }

    public function setEnergyLimit(int $limit) : self
    {
        return $this->state(['energy_max' => $limit]);
    }

    public function setEnergyCharges(int $charges) : self
    {
        return $this->state(['energy_charges' => $charges]);
    }

    public function maxEnergy() : self
    {
        return $this->state(function($data){
            return ['energy' => $data['energy_max']];
        });
    }

    public function setEnergy(int $value) : self
    {
        return $this->state(['energy' => $value]);
    }

}
