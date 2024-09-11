<?php

namespace Modules\Order\Models;

use App\Models\User;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Exceptions\OrderMissingOrderLineException;
use Modules\Payment\Payment;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\DTOs\CartItem;
use NumberFormatter;

class Order extends Model
{
    use HasFactory;

    public const COMPLETED = 'completed';

    public const PENDING = 'pending';

    public const PAYMENT_FAILED = 'payment_failed';

    protected $fillable = [
        'user_id',
        'status',
        'total_in_cents',
    ];

    protected $casts = [
        'user_id'        => 'integer',
        'total_in_cents' => 'integer',
    ];

    protected static function newFactory(): OrderFactory
    {
        return new OrderFactory();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lastPayment(): HasOne
    {
        return $this->payments()->one()->latest();
    }

    public function url(): string
    {
        return route('order::orders.show', $this);
    }

    public static function startForUser(int $userId): Order
    {
        return self::query()->make([
            'user_id' => $userId,
            'status'  => self::PENDING,
        ]);
    }

    public function addLines(array $lines): void
    {
        foreach ($lines as $line) {
            $this->lines->push($line);
        }

        $this->total_in_cents = $this->lines->sum(fn (OrderLine $line) => $line->total());
    }

    /**
     * @param CartItemCollection<CartItem> $items
     *
     * @return void
     */
    public function addLinesFromCartItems(CartItemCollection $items): void
    {
        foreach ($items->items() as $item) {
            // 关系对应的属性是一个 collection?
            $this->lines->push(OrderLine::query()->make([
                'product_id'             => $item->product->id,
                'product_price_in_cents' => $item->product->priceInCents,
                'quantity'               => $item->quantity,
            ]));
        }

        $this->total_in_cents = $this->lines->sum(fn (OrderLine $line) => $line->product_price_in_cents * $item->quantity);
    }

    public function markAsFailed(): void
    {
        if ($this->isCompleted()) {
            throw new RuntimeException('A completed order cannot be marked as failed.');
        }

        $this->status = self::PAYMENT_FAILED;

        $this->save();
    }

    public function isCompleted(): bool
    {
        return $this->status === self::COMPLETED;
    }

    public function complete(): void
    {
        $this->status = self::COMPLETED;
        $this->save();
    }

    /**
     * @throws OrderMissingOrderLineException
     */
    public function start(): void
    {
        if ($this->lines->isEmpty()) {
            throw new OrderMissingOrderLineException();
        }

        $this->status = self::PENDING;

        $this->save();
        $this->lines()->saveMany($this->lines);
    }

    public function localizedTotal(): string
    {
        return (new NumberFormatter('en-US', NumberFormatter::CURRENCY))->formatCurrency($this->total_in_cents / 100, 'USD');
    }
}
