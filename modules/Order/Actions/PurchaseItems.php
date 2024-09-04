<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManager $productStockManager,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
    )
    {
    }

    /**
     * @param CartItemCollection $items
     * @param PayBuddy           $paymentProvider
     * @param string             $paymentToken
     * @param int                $userId
     *
     * @return Order
     *
     * @throws PaymentFailedException
     */
    public function handle(CartItemCollection $items, PayBuddy $paymentProvider, string $paymentToken, int $userId): Order
    {
        return $this->databaseManager->transaction(function () use ($paymentToken, $paymentProvider, $items, $userId) {
            // 前两个方法只在内存中操作, 最后用一个单独的方法写入数据库, 这样做有什么好处吗, 方便回滚吗, 暂时只想到了这一个好处
            $order = Order::startForUser($userId);
            $order->addLinesFromCartItems($items);
            $order->fulfill();

            foreach ($items->items() as $cartItem) {
                $this->productStockManager->decrement($cartItem->product->id, $cartItem->quantity);
            }

            $this->createPaymentForOrder->handle($order->id, $userId, $items->totalInCents(), $paymentProvider, $paymentToken);

            return $order;
        });
    }
}
