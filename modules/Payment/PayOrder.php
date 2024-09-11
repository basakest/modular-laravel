<?php

namespace Modules\Payment;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderStarted;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\Exceptions\PaymentFailedException;

class PayOrder implements ShouldQueue
{
    public function __construct(
        protected CreatePaymentForOrderInterface $createPaymentForOrder,
        protected Dispatcher $events,
    )
    {
    }

    public function handle(OrderStarted $event): void
    {
        try {
            $this->createPaymentForOrder->handle(
                $event->order->id,
                $event->user->id,
                $event->order->orderInCents,
                $event->pendingPayment->provider,
                $event->pendingPayment->paymentToken
            );
        } catch (PaymentFailedException $e) {
            $this->events->dispatch(
                new PaymentFailed($event->order, $event->user, $e->getMessage())
            );

            throw $e;
        }

        $this->events->dispatch(
            new PaymentSucceed($event->order, $event->user)
        );
    }
}
