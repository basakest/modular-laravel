<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Modules\Order\Events\OrderFulfilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Throwable;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events,
    )
    {
    }

    /**
     * @param CartItemCollection $items
     * @param PayBuddy           $paymentProvider
     * @param string             $paymentToken
     * @param int                $userId
     * @param string             $userEmail
     *
     * @return Order
     *
     * @throws Throwable
     */
    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId, string $userEmail): Order
    {
        /** @var Order $order */
        $order = $this->databaseManager->transaction(function () use ($paymentToken, $paymentProvider, $items, $userId) {
            // 前两个方法只在内存中操作, 最后用一个单独的方法写入数据库, 这样做有什么好处吗, 方便回滚吗, 暂时只想到了这一个好处
            $order = Order::startForUser($userId);
            $order->addLinesFromCartItems($items);
            $order->fulfill();

            $this->createPaymentForOrder->handle($order->id, $userId, $items->totalInCents(), $paymentProvider, $paymentToken);

            return $order;
        });

        $this->events->dispatch(new OrderFulfilled(
            orderId: $order->id,
            totalInCents: $order->total_in_cents,
            localizedTotal: $order->localizedTotal(),
            cartItems: $items,
            userId: $userId,
            userEmail: $userEmail
        ));

        return $order;
    }
}
