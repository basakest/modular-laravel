<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Payment\PaymentSucceed;
use Modules\Product\Events\DecreaseProductStock;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        PaymentSucceed::class => [
            DecreaseProductStock::class,
        ],
    ];
}