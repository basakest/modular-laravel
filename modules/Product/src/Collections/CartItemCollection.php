<?php

namespace Modules\Product\Collections;

use Illuminate\Support\Collection;
use Modules\Product\DTOs\CartItem;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Models\Product;

class CartItemCollection
{
    /**
     * @param Collection<CartItem> $items
     */
    public function __construct(
        protected Collection $items,
    )
    {
    }

    public static function fromProduct(ProductDto $product, int $quantity = 1): CartItemCollection
    {
        return new self(collect([
            new CartItem($product, $quantity),
        ]));
    }

    public static function fromCheckoutData(array $data): CartItemCollection
    {
        // DB::listen(function ($query) {
        //     logger($query->sql, $query->bindings);
        // });
        $cartData = collect($data);
        $products = Product::query()->whereIn('id', $cartData->pluck('id'))->get();
        $cartItems = $products->map(function (Product $productModel) use ($cartData) {
            $cartItem = $cartData->where('id', $productModel->id)->first();
            return new CartItem(
                ProductDto::fromEloquentModel($productModel),
                $cartItem['quantity'],
            );
        });
        // logger('>>>>>>>>>>');

        return new self($cartItems);
    }

    public function totalInCents()
    {
        return $this->items->sum(function (CartItem $cartItem) {
            return $cartItem->quantity * $cartItem->product->priceInCents;
        });
    }

    /**
     * @return Collection<CartItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }
}