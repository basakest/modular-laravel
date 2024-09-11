<?php

use Illuminate\Support\Facades\Event;
use Modules\Order\Events\OrderStarted;
use Modules\Order\Events\SendOrderConfirmationEmail;
use Modules\Payment\PayOrder;

it('can listen OrderStarted event', function () {
    Event::fake();

    Event::assertListening(OrderStarted::class, SendOrderConfirmationEmail::class);
    Event::assertListening(OrderStarted::class, PayOrder::class);
});
