<?php

use Modules\Product\Product;

it('can create a product', function () {
    $order = Product::factory()->create();
    dd($order);
    expect($order)->toBeInstanceOf(Product::class);
});
