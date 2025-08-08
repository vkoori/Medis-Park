<?php

namespace Modules\Post\Repositories;

use Modules\Post\Enums\UserPostVisitEnum;
use Modules\Post\Models\UserPostVisit;
use App\Utils\Repository\BaseRepository;

/**
 * @extends BaseRepository<UserPostVisit>
 */
class UserPostVisitRepository extends BaseRepository
{
    public function __construct(private UserPostVisit $userPostVisit) {}

    public function getModel(): UserPostVisit
    {
        return $this->userPostVisit;
    }

    public function getLastNormalUnlocked(int $userId): ?UserPostVisit
    {
        return $this->getModel()
            ->query()
            ->where('user_id', $userId)
            ->where('type', UserPostVisitEnum::NORMAL)
            ->latest('id')
            ->first();
    }
}
