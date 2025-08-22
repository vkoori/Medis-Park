<?php

namespace Modules\Order\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Order\Enums\OrderStatusEnum;

class OrderService
{
    use ClassResolver;

    public function buyPost(int $userId, int $postId): void
    {
        $amount = $this->getPostPriceService()->getLastPostPrice();

        DB::transaction(function () use ($userId, $postId, $amount) {
            $order = $this->getOrderRepository()->create(attributes: [
                'user_id' => $userId,
                'product_id' => null,
                'coin_id' => null,
                'post_id' => $postId,
                'status' => OrderStatusEnum::PAID,
                'coin_value' => $amount,
                'used_at' => null,
            ]);

            $this->getCoinTransactionService()->transaction(
                userId: $userId,
                amount: $amount,
                reason: __('order::messages.buy_post'),
                type: TransactionReferenceTypeEnum::ORDER,
                referenceId: $order->id
            );
        });
    }
}
