<?php

namespace Modules\User\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\User\Enums\OtpTypeEnum;
use Modules\User\Models\UserOtp;

/**
 * @extends BaseRepository<UserOtp>
 */
class UserOtpRepository extends BaseRepository
{
    public function __construct(private UserOtp $otp) {}

    protected function getModel(): UserOtp
    {
        return $this->otp;
    }

    public function findActiveOtp(int $userId, OtpTypeEnum $type, array $relations = []): ?UserOtp
    {
        return $this->getModel()
            ->newQuery()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->with($relations)
            ->latest('id')
            ->first();
    }
}
