<?php

namespace Modules\Order\Actions;

use App\Models\UserDto;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderStarted;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\OrderFulfilled;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Throwable;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrderInterface $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events,
    )
    {
    }

    /**
     * @param CartItemCollection $items
     * @param PendingPayment     $pendingPayment
     * @param UserDto            $user
     *
     * @return OrderDto
     *
     * @throws Throwable
     */
    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $user): OrderDto
    {
        /** @var OrderDto $order */
        $order = $this->databaseManager->transaction(function () use ($items, $user) {
            // 前两个方法只在内存中操作, 最后用一个单独的方法写入数据库, 这样做有什么好处吗, 方便回滚吗, 暂时只想到了这一个好处
            $order = Order::startForUser($user->id);
            $order->addLinesFromCartItems($items);
            $order->start();

            return OrderDto::fromEloquentModel($order);
        });

        $this->events->dispatch(new OrderStarted($order, $user, $pendingPayment));

        return $order;
    }
}
