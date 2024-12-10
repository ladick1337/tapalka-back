<?php

namespace App\Exceptions\Client;

class NoFundsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No funds');
    }
}
