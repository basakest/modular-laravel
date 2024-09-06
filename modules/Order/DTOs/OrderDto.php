<?php

namespace Modules\Order\DTOs;

use Modules\Order\Models\Order;

readonly class OrderDto
{
    /**
     * @param int    $id
     * @param int    $orderInCents
     * @param string $localizedTotal
     * @param string $url
     * @param array<OrderLineDto>  $lines
     */
    public function __construct(
        public int $id,
        public int $orderInCents,
        public string $localizedTotal,
        public string $url,
        public array $lines,
    )
    {
    }

    public static function fromEloquentModel(Order $order): OrderDto
    {
        return new self(
            $order->id,
            $order->total_in_cents,
            $order->localizedTotal(),
            $order->url(),
            OrderLineDto::fromEloquentCollection($order->lines),
        );
    }
}
