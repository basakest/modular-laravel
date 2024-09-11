<?php

namespace Modules\Order\Events;

use App\Models\UserDto;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;

class OrderStarted
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
        public PendingPayment $pendingPayment,
    )
    {
    }
}