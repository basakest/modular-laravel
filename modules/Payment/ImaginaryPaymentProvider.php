<?php

namespace Modules\Payment;

use http\Exception\RuntimeException;
use Illuminate\Support\Str;

final class ImaginaryPaymentProvider
{
    public function charge(string $token, int $amountInCents, string $statementDescription): array
    {
        $this->validateToken($token);

        $numberFormat = new \NumberFormatter('en-US', \NumberFormatter::CURRENCY);

        return [
            'id'                    => strval(Str::uuid()),
            'amount_in_cents'       => $amountInCents,
            'localized_amount'      => $numberFormat->format($amountInCents / 100),
            'statement_description' => $statementDescription,
            'created_at'            => now()->toDateTimeString(),
        ];
    }

    public static function make(): ImaginaryPaymentProvider
    {
        return new self();
    }

    /**
     * @param string $token
     *
     * @return void
     * @throws \RuntimeException
     *
     */
    protected function validateToken(string $token): void
    {
        if (!Str::isUlid($token)) {
            throw new RuntimeException('The given payment token is not valid.');
        }
    }
}