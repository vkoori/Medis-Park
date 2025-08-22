<?php

namespace Modules\Coin\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Coin\Enums\TransactionStatusEnum;
use Modules\Coin\Models\CoinTransaction;

/**
 * @extends BaseRepository<CoinTransaction>
 */
class CoinTransactionRepository extends BaseRepository
{
    public function __construct(private CoinTransaction $coinTransaction) {}

    protected function getModel(): CoinTransaction
    {
        return $this->coinTransaction;
    }

    public function getBalance(int $userId): int
    {
        return $this->getModel()
            ->query()
            ->where('user_id', $userId)
            ->where('status', '<>', TransactionStatusEnum::CANCELLED)
            ->sum('amount');
    }
}
