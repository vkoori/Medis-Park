<?php

namespace Modules\Post\Repositories;

use Carbon\Carbon;
use Modules\Post\Models\Post;
use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends BaseRepository<Post>
 */
class PostRepository extends BaseRepository
{
    public function __construct(private Post $post) {}

    public function getModel(): Post
    {
        return $this->post;
    }

    public function getRandomAvailablePostForUser($userId): ?Post
    {
        $now = now();

        return $this->getModel()
            ->query()
            ->where('available_at', '<=', $now)
            ->where('expired_at', '>=', $now)
            ->whereDoesntHave('seen', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->inRandomOrder()
            ->with(['media'])
            ->first();
    }

    public function getUnlockedPosts(int $userId, Carbon $from, Carbon $toExclusive)
    {
        return $this->getModel()
            ->query()
            ->whereHas('seen', function ($postVisit) use ($userId, $from, $toExclusive) {
                $postVisit
                    ->where('user_id', $userId)
                    ->where('calendar_day', '>=', $from)
                    ->where('calendar_day', '<', $toExclusive);
            })
            ->with(['media'])
            ->get();
    }

    protected function fetchData(?array $conditions, array $relations, ?Builder $query = null): Builder
    {
        $q = $this->getModel()->query();

        if (isset($conditions['from'])) {
            $q->where('expired_at', '>=', $conditions['from']);
            unset($conditions['from']);
        }
        if (isset($conditions['to'])) {
            $q->where('available_at', '<=', $conditions['to']);
            unset($conditions['to']);
        }
        if (isset($conditions['title'])) {
            $q->whereLike('title', "%{$conditions['title']}%");
            unset($conditions['title']);
        }

        return parent::fetchData(conditions: $conditions, relations: $relations, query: $q);
    }
}
