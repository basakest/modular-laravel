<?php

use Modules\Product\Product;

it('can create a product', function () {
    $order = new Product();
    expect($order)->toBeInstanceOf(Product::class);
});
