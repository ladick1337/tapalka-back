<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Sending;
use Illuminate\Database\Eloquent\Factories\Factory;

class SendingFactory extends Factory
{
    protected $model = Sending::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function text(string $text) : self
    {
        return $this->state(['text' => $text]);
    }

    public function lang(string $lang) : self
    {
        return $this->state(['lang' => $lang]);
    }

    public function updateUsersCount() : self
    {
        return $this->state(function($data){

            return [
                'users_all' => Client::alive()
                    ->where('lang', $data['lang'])
                    ->count()
            ];

        });
    }

}
