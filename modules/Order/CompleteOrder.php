<?php

namespace Modules\Order;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Models\Order;
use Modules\Payment\PaymentSucceed;

class CompleteOrder implements ShouldQueue
{
    public function handle(PaymentSucceed $event): void
    {
        Order::query()->find($event->order->id)->complete();
    }
}