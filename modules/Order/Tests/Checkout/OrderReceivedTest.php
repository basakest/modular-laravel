<?php

use Modules\Order\DTOs\OrderDto;
use Modules\Order\Mail\OrderReceived;

it('can render the mailable', function () {
    $orderDto = new OrderDto(
        id: 1,
        orderInCents: 99_00,
        localizedTotal: '$99.00',
        url: route('order::orders.show', 1),
        lines: [],
    );

    $orderReceived = new OrderReceived($orderDto);

    expect($orderReceived->render())->toBeString();
});
