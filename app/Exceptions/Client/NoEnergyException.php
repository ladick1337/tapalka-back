<?php

namespace App\Exceptions\Client;

class NoEnergyException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No energy');
    }
}
