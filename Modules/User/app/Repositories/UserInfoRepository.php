<?php

namespace Modules\User\Repositories;

use App\Utils\Repository\BaseRepository;
use Illuminate\Support\Facades\Date;
use Modules\User\Models\UserInfo;

/**
 * @extends BaseRepository<UserInfo>
 */
class UserInfoRepository extends BaseRepository
{
    public function __construct(private UserInfo $userInfo) {}

    protected function getModel(): UserInfo
    {
        return $this->userInfo;
    }

    public function insertOrIgnore(array $values): bool
    {
        if ($this->getModel()->timestamps) {
            $now = Date::now();
            if ($this->getModel()->getCreatedAtColumn()) {
                $values[$this->getModel()->getCreatedAtColumn()] = $now;
            }
            if ($this->getModel()->getUpdatedAtColumn()) {
                $values[$this->getModel()->getUpdatedAtColumn()] = $now;
            }
        }

        return $this->getModel()->insertOrIgnore($values);
    }
}
