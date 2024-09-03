<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace Modules\Order{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $total_in_cents
 * @property string $status
 * @property string $payment_gateway
 * @property string $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order wherePaymentGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereTotalInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace Modules\Order{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $product_price_in_cents
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereProductPriceInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\OrderLine whereUpdatedAt($value)
 */
	class OrderLine extends \Eloquent {}
}

namespace Modules\Product{
/**
 * 
 *
 * @property int $id
 * @property int $quantity
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUserId($value)
 */
	class CartItem extends \Eloquent {}
}

namespace Modules\Product{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $price_in_cents
 * @property int $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Modules\Product\Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product wherePriceInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models\Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace Modules\Shipment{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $provider
 * @property string $provider_shipment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereProviderShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereUpdatedAt($value)
 */
	class Shipment extends \Eloquent {}
}

