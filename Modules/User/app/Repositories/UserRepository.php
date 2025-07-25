<?php

namespace Modules\User\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\User\Models\User;

/**
 * @extends BaseRepository<User>
 */
class UserRepository extends BaseRepository
{
    public function __construct(private User $user) {}

    protected function getModel(): User
    {
        return $this->user;
    }
}
