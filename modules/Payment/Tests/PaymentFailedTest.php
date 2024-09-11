<?php

use Illuminate\Support\Facades\Event;
use Modules\Order\Actions\MarkOrderAsFailed;
use Modules\Order\Actions\NotifyUserOfPaymentFailure;
use Modules\Payment\PaymentFailed;

it('has listeners', function () {
    Event::fake();

    Event::assertListening(PaymentFailed::class, NotifyUserOfPaymentFailure::class);
    Event::assertListening(PaymentFailed::class, MarkOrderAsFailed::class);
});
