<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\Prize;
use App\Utils\Repository\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<Prize>
 */
class PrizeRepository extends BaseRepository
{
    public function __construct(private Prize $prize) {}

    protected function getModel(): Prize
    {
        return $this->prize;
    }

    /** @return LengthAwarePaginator<Prize> */
    public function paginateCustomer(int $userId, ?array $conditions = null, array $relations = [], ?int $perPage = null): LengthAwarePaginator
    {
        $query = $this->getModel()
            ->query()
            ->withCount([
                'prizeUnlocks as customer_unlocked' => function ($q) use ($userId) {
                    $q->where('user_Id', $userId);
                }
            ]);

        return $this->fetchData($conditions, $relations, $query)->paginate($perPage);
    }

    public function latestUnlock(int $userId, string $month): ?Prize
    {
        return $this->getModel()
            ->query()
            ->where('month', $month)
            ->whereHas('prizeUnlocks', fn($q) => $q->where('user_id', $userId))
            ->with(['prizeUnlocks' => fn($q) => $q->where('user_id', $userId)->latest()->limit(1)])
            ->first();
    }
}
