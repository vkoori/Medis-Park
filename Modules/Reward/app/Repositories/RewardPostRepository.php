<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\RewardPost;
use App\Utils\Repository\BaseRepository;

class RewardPostRepository extends BaseRepository
{
    public function __construct(private RewardPost $rewardPost) {}

    public function getModel(): rewardPost
    {
        return $this->rewardPost;
    }
}
