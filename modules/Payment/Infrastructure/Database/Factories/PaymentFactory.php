<?php

namespace Modules\Payment\Infrastructure\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Payment\Payment;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_in_cents'  => random_int(100, 10000),
            'status'          => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id'      => strval(Str::uuid()),
            'user_id'         => User::factory()->create(),
        ];
    }
}
