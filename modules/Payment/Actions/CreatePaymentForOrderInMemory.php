<?php

namespace Modules\Payment\Actions;

use Illuminate\Support\Str;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Payment;
use Modules\Payment\PaymentGateway;
use Modules\Payment\PaymentProvider;

class CreatePaymentForOrderInMemory implements CreatePaymentForOrderInterface
{
    /**
     * @var array<Payment>
     */
    public array $payments = [];

    protected bool $shouldFail = false;

    public function handle(int $orderId, int $userId, int $totalInCents, PaymentGateway $paymentGateway, string $paymentToken,): Payment
    {
        if ($this->shouldFail) {
            throw new PaymentFailedException();
        }
        $payment = new Payment([
            'total_in_cents'  => $totalInCents,
            'payment_gateway' => PaymentProvider::InMemory,
            'payment_id'      => (string) Str::uuid(),
            'user_id'         => $userId,
            'order_id'        => $orderId,
        ]);

        $this->payments[] = $payment;

        return $payment;
    }

    public function shouldFail(): void
    {
        $this->shouldFail = true;
    }
}