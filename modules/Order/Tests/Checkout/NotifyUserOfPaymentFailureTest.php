<?php

use App\Models\User;
use App\Models\UserDto;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Actions\NotifyUserOfPaymentFailure;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\Mail\PaymentForOrderFailed;
use Modules\Order\Models\Order;
use Modules\Payment\PaymentFailed;

it('can notifies the user if the payment failure', function () {
    Mail::fake();

    $order = Order::factory()->create();
    $orderDto = OrderDto::fromEloquentModel($order);
    $userDto = UserDto::fromEloquentModel(User::factory()->create());

    $event = new PaymentFailed($orderDto, $userDto, 'Payment failed.');
    /** @var NotifyUserOfPaymentFailure $noticeAction */
    $noticeAction = app(NotifyUserOfPaymentFailure::class);
    $noticeAction->handle($event);

    Mail::assertSent(PaymentForOrderFailed::class, fn (PaymentForOrderFailed $mailable) => $mailable->hasTo($userDto->email));
});
