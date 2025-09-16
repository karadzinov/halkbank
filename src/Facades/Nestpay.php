<?php

namespace Nestpay\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Nestpay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nestpay\Laravel\Contracts\NestpayContract::class;
    }
}


