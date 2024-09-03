<?php

use Modules\Order\Models\Order;

it('can create an order', function () {
    $order = new Order();
    expect($order)->toBeInstanceOf(Order::class);
});
