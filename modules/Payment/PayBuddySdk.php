<?php

namespace Modules\Payment;

use Illuminate\Support\Str;

final class PayBuddySdk
{
    /**
     * @param string $token
     * @param int    $amountInCents
     * @param string $statementDescription
     *
     * @throws \RuntimeException
     *
     * @return array
     */
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

    public static function make(): PayBuddySdk
    {
        return new self();
    }

    public static function validToken(): string
    {
        return strval(Str::uuid());
    }

    public static function invalidToken(): string
    {
        return substr(self::validToken(), -35);
    }

    /**
     * @param string $token
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function validateToken(string $token): void
    {
        if (! Str::isUuid($token)) {
            throw new \RuntimeException('The given payment token is not valid.');
        }
    }
}