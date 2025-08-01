<?php

namespace Modules\Coin\Repositories;

use App\Utils\Repository\BaseRepository;
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
}
