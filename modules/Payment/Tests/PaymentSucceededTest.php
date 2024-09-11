<?php

use Illuminate\Support\Facades\Event;
use Modules\Order\CompleteOrder;
use Modules\Payment\PaymentSucceed;
use Modules\Product\Events\DecreaseProductStock;

it('', function () {
    Event::fake();

    Event::assertListening(PaymentSucceed::class, DecreaseProductStock::class);
    Event::assertListening(PaymentSucceed::class, CompleteOrder::class);
});
