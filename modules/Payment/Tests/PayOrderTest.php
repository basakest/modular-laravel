<?php

use App\Models\UserDto;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Event;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderStarted;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderLine;
use Modules\Payment\Actions\CreatePaymentForOrderInMemory;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\PayBuddySdk;
use Modules\Payment\PaymentFailed;
use Modules\Payment\PaymentGateway;
use Modules\Payment\PaymentProvider;
use Modules\Payment\PaymentSucceed;
use Modules\Payment\PayOrder;

it('can be queued', function () {
    expect(app(PayOrder::class))->toBeInstanceOf(ShouldQueue::class);
});

it('can pay for an order', function () {
    Event::fake();

    /** @var Order $order*/
    $order = Order::factory()->create();
    $order->addLines([
        OrderLine::factory()->create([
            'product_price_in_cents' => 99_00,
        ])
    ]);
    $order->start();

    $event = new OrderStarted(
        OrderDto::fromEloquentModel($order),
        UserDto::fromEloquentModel($order->user),
        new PendingPayment(app(PaymentGateway::class), PayBuddySdk::validToken()),
    );

    /** @var PayOrder $payOrder */
    $payOrder = app(PayOrder::class);
    $payOrder->handle($event);

    $payment = $order->lastPayment;
    expect($payment->status)->toEqual('paid')
        ->and($payment->payment_gateway)->toEqual(PaymentProvider::PayBuddy)
        ->and($payment->payment_id)->toHaveLength(36)
        ->and($payment->total_in_cents)->toEqual(99_00)
        ->and($payment->user)->toEqual($order->user);

    Event::assertDispatched(PaymentSucceed::class);
});

it('can handle a payment failure', function () {
    $createPayment = new CreatePaymentForOrderInMemory();
    $createPayment->shouldFail();
    app()->instance(CreatePaymentForOrderInterface::class, $createPayment);

    Event::fake();

    /** @var Order $order */
    $order = Order::factory()->create();
    $order->addLines([
        OrderLine::factory()->create([
            'product_price_in_cents' => 99_00,
        ])
    ]);

    $order->start();

    $event = new OrderStarted(
        OrderDto::fromEloquentModel($order),
        UserDto::fromEloquentModel($order->user),
        new PendingPayment(app(PaymentGateway::class), PayBuddySdk::validToken())
    );

    try {
        /** @var PayOrder $payOrder */
        $payOrder = app(PayOrder::class);
        $payOrder->handle($event);
    } finally {
        // 抛出异常时这里也会执行?
        $order->refresh();

        Event::assertDispatched(PaymentFailed::class);
        Event::assertNotDispatched(PaymentSucceed::class);
    }
})->expectException(PaymentFailedException::class);
