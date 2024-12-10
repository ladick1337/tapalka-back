<?php

namespace App\Exceptions\Client;

class NoEnergyChargesException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No energy charges');
    }
}
