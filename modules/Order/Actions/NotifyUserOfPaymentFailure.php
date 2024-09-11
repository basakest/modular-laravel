<?php

namespace Modules\Order\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\PaymentForOrderFailed;
use Modules\Payment\PaymentFailed;

class NotifyUserOfPaymentFailure implements ShouldQueue
{
    public function handle(PaymentFailed $event): void
    {
        Mail::to($event->user->email)->send(new PaymentForOrderFailed($event->order, $event->reason));
    }
}
