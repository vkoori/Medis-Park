<?php

namespace Modules\Coin\Services;

use App\Exceptions\NotImplementedException;
use App\Traits\ClassResolver;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Coin\Enums\TransactionStatusEnum;
use Modules\Coin\Models\CoinTransaction;

class CoinTransactionService
{
    use ClassResolver;

    public function transaction(
        int $userId,
        int $amount,
        string $reason,
        TransactionReferenceTypeEnum $type,
        int $referenceId,
    ): CoinTransaction {
        $amount = abs(num: $amount);
        [$amount, $status] = match ($type) {
            TransactionReferenceTypeEnum::REWARD_UNLOCKED => [$amount, TransactionStatusEnum::CONFIRMED],
            TransactionReferenceTypeEnum::CRM,
            TransactionReferenceTypeEnum::ORDER => [-1 * $amount, TransactionStatusEnum::PENDING],
            default => throw new NotImplementedException(),
        };

        return $this->getCoinTransactionRepository()->create(attributes: [
            'user_id' => $userId,
            'amount' => $amount,
            'reason' => $reason,
            'reference_type' => $type,
            'reference_id' => $referenceId,
            'status' => $status,
        ]);
    }
}
