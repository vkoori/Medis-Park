<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\PrizeUnlock;
use App\Utils\Repository\BaseRepository;
use Modules\Reward\Enums\PrizeUnlockTypeEnum;

/**
 * @extends BaseRepository<PrizeUnlock>
 */
class PrizeUnlockRepository extends BaseRepository
{
    public function __construct(private PrizeUnlock $prizeUnlock) {}

    protected function getModel(): PrizeUnlock
    {
        return $this->prizeUnlock;
    }

    public function getLastNormalUnlocked(int $userId)
    {
        return $this->last(conditions: [
            'user_id' => $userId,
            'type' => PrizeUnlockTypeEnum::NORMAL
        ]);
    }
}
