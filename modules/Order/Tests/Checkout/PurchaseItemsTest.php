<?php

use App\Models\User;
use App\Models\UserDto;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderFulfilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrderInMemory;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\InMemoryGateway;
use Modules\Payment\PayBuddySdk;
use Modules\Payment\Payment;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Models\Product;

it('can create an order', function () {
    Mail::fake();
    Event::fake();

    $user = User::factory()->create();
    /** @var Product $product */
    $product = Product::factory()->create([
        'stock' => 10,
        'price_in_cents' => 100_00,
    ]);

    $createPayment = new CreatePaymentForOrderInMemory();
    $this->instance(CreatePaymentForOrderInterface::class, $createPayment);

    $cartItemCollection = CartItemCollection::fromProduct(ProductDto::fromEloquentModel($product), 2);
    $pendingPayment = new PendingPayment(new InMemoryGateway(), PayBuddySdk::validToken());
    $userDto = UserDto::fromEloquentModel($user);

    /** @var PurchaseItems $purchaseItems*/
    $purchaseItems = app(PurchaseItems::class);
    $order = $purchaseItems->handle($cartItemCollection, $pendingPayment, $userDto);

    $orderLine = $order->lines[0];

    expect($order->orderInCents)->toBe($product->price_in_cents * 2)
        ->and($order->lines)->toHaveCount(1)
        ->and($orderLine->productId)->toEqual($product->id)
        ->and($orderLine->productPriceInCents)->toEqual($product->price_in_cents)
        ->and($orderLine->quantity)->toEqual(2);

    $payment = $createPayment->payments[0];
    expect($createPayment->payments)->toHaveCount(1)
        ->and($payment->user_id)->toEqual($userDto->id);

    Event::assertDispatched(OrderFulfilled::class, function (OrderFulfilled $event) use ($userDto, $order) {
        return $event->order === $order && $event->user === $userDto;
    });
});

it('does not create an order if something fails', function () {
    Mail::fake();
    Event::fake();

    $createPayment = new CreatePaymentForOrderInMemory();
    $createPayment->shouldFail();
    $this->instance(CreatePaymentForOrderInterface::class, $createPayment);

    $user = User::factory()->create();
    $product = Product::factory()->create();

    $cartItemCollection = CartItemCollection::fromProduct(ProductDto::fromEloquentModel($product), 2);
    $pendingPayment = new PendingPayment(new InMemoryGateway(), PayBuddySdk::validToken());
    $userDto = UserDto::fromEloquentModel($user);

    /** @var PurchaseItems $purchaseItems */
    $purchaseItems = app(PurchaseItems::class);

    try {
        $purchaseItems->handle($cartItemCollection, $pendingPayment, $userDto);
    } finally {
        expect(Order::query()->count())->toEqual(0)
            ->and(Payment::query()->count())->toEqual(0)
            ->and($createPayment->payments)->toHaveCount(0);
        Event::assertNotDispatched(OrderFulfilled::class);
    }
})->throws(PaymentFailedException::class);
