<?php

namespace Modules\Order\Events;

use App\Models\UserDto;
use Modules\Order\DTOs\OrderDto;

readonly class OrderFulfilled
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    )
    {
    }
}
