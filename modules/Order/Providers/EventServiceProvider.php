<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Actions\MarkOrderAsFailed;
use Modules\Order\Actions\NotifyUserOfPaymentFailure;
use Modules\Order\CompleteOrder;
use Modules\Order\Events\OrderStarted;
use Modules\Order\Events\SendOrderConfirmationEmail;
use Modules\Payment\PaymentFailed;
use Modules\Payment\PaymentSucceed;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderStarted::class => [
            SendOrderConfirmationEmail::class,
        ],
        PaymentFailed::class => [
            MarkOrderAsFailed::class,
            NotifyUserOfPaymentFailure::class,
        ],
        PaymentSucceed::class => [
            CompleteOrder::class,
        ],
    ];
}
