<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderReceived;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderLine;
use Modules\Payment\PayBuddy;
use Modules\Payment\Payment;
use Modules\Product\Models\Product;

it('can create an order successfully', function () {
    Mail::fake();
    // 这里获取到的 User 实例的 table 属性为 null, 在 User 类中指定 table 后正常返回
    $user = User::factory()->create();
    $products = Product::factory(2)->create(
        new Sequence(
            ['name' => 'Very expensive air fryer', 'price_in_cents' => 10000, 'stock' => 10],
            ['name' => 'Macbook Pro M3', 'price_in_cents' => 50000, 'stock' => 10]
        )
    );
    $paymentToken = PayBuddy::validToken();

    $response = $this->actingAs($user)->post(route('order::checkout', [
        'payment_token' => $paymentToken,
        'products'      => [
            ['id' => $products->first()->id, 'quantity' => 1],
            ['id' => $products->last()->id, 'quantity' => 1],
        ],
    ]));

    $order = Order::query()->latest('id')->first();

    $response->assertJson(['order_url' => $order->url()])->assertStatus(201);

    Mail::assertSent(OrderReceived::class, function (OrderReceived $mail) use ($user) {
        return $mail->hasTo($user->email);
    });

    expect($order->user)->toEqual($user)
        ->and($order->total_in_cents)->toBe(60000)
        ->and($order->status)->toBe('completed')
        ->and($order->lines)->toHaveCount(2);

    // payment
    /** @var $payment Payment */
    $payment = $order->lastPayment;
    expect($payment->status)->toBe('paid')
        ->and($payment->payment_gateway)->toBe('PayBuddy')
        ->and(strlen($payment->payment_id))->toBe(36)
        ->and($payment->total_in_cents)->toBe(60000)
        ->and($payment->user)->toEqual($user);

    foreach ($products as $product) {
        /** @var OrderLine $orderLine */
        $orderLine = $order->lines->where('product_id', $product->id)->first();

        expect($orderLine->quantity)->toBe(1)
            ->and($orderLine->product_price_in_cents)->toBe($product->price_in_cents);
    }

    $products = $products->fresh();
    expect($products->first()->stock)->toBe(9)
        ->and($products->last()->stock)->toBe(9);
});

it('will fails when using an invalid token', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $paymentToken = PayBuddy::invalidToken();

    $response = $this->actingAs($user)
        ->postJson(route('order::checkout', [
            'payment_token' => $paymentToken,
            'products'      => [
                ['id' => $product->id, 'quantity' => 1],
            ],
        ]));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['payment_token']);

    expect(Order::query()->count())->toBe(0);
});
