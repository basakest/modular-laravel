<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderLine;
use Modules\Product\Models\Product;

class OrderLineFactory extends Factory
{
    protected $model = OrderLine::class;

    public function definition(): array
    {
        return [
            'order_id'               => Order::factory(),
            'product_id'             => Product::factory(),
            'product_price_in_cents' => $this->faker->randomNumber(),
            'quantity'               => 1,
        ];
    }
}