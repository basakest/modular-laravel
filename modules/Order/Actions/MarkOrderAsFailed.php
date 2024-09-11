<?php

namespace Modules\Order\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Models\Order;
use Modules\Payment\PaymentFailed;

class MarkOrderAsFailed implements ShouldQueue
{
    public function handle(PaymentFailed $event): void
    {
        Order::query()->find($event->order->id)->markAsFailed();
    }
}
