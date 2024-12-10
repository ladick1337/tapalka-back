<?php

namespace Database\Factories;

use App\Models\AdminAuthHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminAuthHistoryFactory extends Factory
{

    protected $model = AdminAuthHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }

    public function ip(string $ip) : self
    {
        return $this->state(['ip' => $ip]);
    }

}
