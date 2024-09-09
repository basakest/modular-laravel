<?php

namespace Modules\Payment;

use Modules\Payment\Exceptions\PaymentFailedException;

class PayBuddyGateway implements PaymentGateway
{
    public function __construct(
        protected PayBuddySdk $payBuddySdk,
    )
    {
    }

    public function charge(PaymentDetails $paymentDetails): SuccessfulPayment
    {
        try {
            $charge = $this->payBuddySdk->charge(
                $paymentDetails->token,
                $paymentDetails->amountInCents,
                $paymentDetails->statementDescription,
            );
        } catch (\RuntimeException $e) {
            throw new PaymentFailedException($e->getMessage());
        }

        return new SuccessfulPayment(
            $charge['id'],
            $charge['amount_in_cents'],
            $this->id(),
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PayBuddy;
    }
}
