<?php

namespace Modules\Payment;

use App\Models\UserDto;
use Modules\Order\DTOs\OrderDto;

readonly class PaymentSucceed
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    )
    {
    }
}
