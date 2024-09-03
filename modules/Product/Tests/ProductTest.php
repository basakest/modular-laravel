<?php

use Modules\Product\Models\Product;

it('can create a product', function () {
    $order = Product::factory()->create();
    expect($order)->toBeInstanceOf(Product::class);
});
