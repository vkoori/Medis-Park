<?php

namespace Modules\Order\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Order\Dto\OrderFilterDto;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Exceptions\OrderExceptions;
use Modules\Order\Models\Order;

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

    public function buyProduct(int $userId, int $productId): void
    {
        $alreadyBuy = $this->getOrderRepository()->hasUnusedProduct(userId: $userId, productId: $productId);
        if ($alreadyBuy) {
            throw OrderExceptions::alreadyBuy();
        }

        $amount = $this->getProductService()->getLastPrice(productId: $productId);

        DB::transaction(function () use ($userId, $productId, $amount) {
            $order = $this->getOrderRepository()->create(attributes: [
                'user_id' => $userId,
                'product_id' => $productId,
                'post_id' => null,
                'status' => OrderStatusEnum::PAID,
                'coin_value' => $amount,
                'used_at' => null,
            ]);

            $this->getCoinTransactionService()->transaction(
                userId: $userId,
                amount: $amount,
                reason: __('order::messages.buy_product'),
                type: TransactionReferenceTypeEnum::ORDER,
                referenceId: $order->id
            );
        });
    }

    public function paginate(OrderFilterDto $dto)
    {
        $conditions = [];
        if ($dto->hasField('mobile')) {
            $conditions['mobile'] = $dto->mobile;
        }
        if ($dto->hasField('unused')) {
            $conditions['unused'] = $dto->unused;
        }

        return $this->getOrderRepository()->paginate(
            conditions: $conditions,
            relations: ['post', 'product', 'user.info']
        );
    }

    public function used(int $orderId): bool
    {
        $order = $this->findOrder(orderId: $orderId);
        if ($order->used_at) {
            throw OrderExceptions::alreadyUsed();
        }

        return $this->getOrderRepository()->usedProduct(orderId: $orderId);
    }

    private function findOrder(int $orderId): Order
    {
        $order = $this->getOrderRepository()->findById(modelId: $orderId);

        if (!$order) {
            throw OrderExceptions::notFound();
        }

        return $order;
    }
}
