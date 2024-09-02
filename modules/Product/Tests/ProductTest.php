<?php

use Modules\Product\Product;

it('can create a product', function () {
    $order = Product::factory()->create();
    expect($order)->toBeInstanceOf(Product::class);
});
