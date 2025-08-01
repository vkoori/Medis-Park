<?php

namespace Modules\Reward\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Reward\Models\ProfileField;

class ProfileFieldRepository extends BaseRepository
{
    public function __construct(private ProfileField $profileField) {}

    protected function getModel(): ProfileField
    {
        return $this->profileField;
    }
}
