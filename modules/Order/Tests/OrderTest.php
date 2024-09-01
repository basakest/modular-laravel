<?php

use Modules\Order\Order;

it('can create an order', function () {
    $order = new Order();
    expect($order)->toBeInstanceOf(Order::class);
});
