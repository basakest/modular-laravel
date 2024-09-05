<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;

class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        try {
            $order = $this->purchaseItems->handle(
                $cartItems,
                PayBuddy::make(),
                $request->input('payment_token'),
                $request->user()->id,
                $request->user()->email,
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment.'
            ]);
        }

        return response()->json([
            'order_url' => $order->url()
        ], 201);
    }
}
