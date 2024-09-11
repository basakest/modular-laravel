<?php

namespace Modules\Payment\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Events\OrderStarted;
use Modules\Payment\PayOrder;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderStarted::class => [
            PayOrder::class,
        ],
    ];
}
