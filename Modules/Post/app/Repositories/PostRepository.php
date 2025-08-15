<?php

namespace Modules\Post\Repositories;

use Carbon\Carbon;
use Modules\Post\Models\Post;
use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;

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

    public function getShuffledPosts(string $month, int $userId, array $relations = [])
    {
        return $this->getModel()
            ->query()
            ->where('month', $month)
            ->withCount([
                'seen as visited' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->with($relations)
            ->orderByRaw("MD5(CONCAT(?, posts.id))", [$userId])
            ->get();
    }

    public function findTodayPost(Jalalian $date, int $userId, array $relations = []): ?Post
    {
        return $this->getModel()
            ->query()
            ->where('month', $date->format('Y-m'))
            ->withCount([
                'seen as visited' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->with($relations)
            ->orderByRaw("MD5(CONCAT(?, posts.id))", [$userId])
            ->offset($date->getDay() - 1)
            ->first();
    }

    protected function fetchData(?array $conditions, array $relations, ?Builder $query = null): Builder
    {
        $q = $this->getModel()->query();

        if (isset($conditions['title'])) {
            $q->whereLike('title', "%{$conditions['title']}%");
            unset($conditions['title']);
        }

        return parent::fetchData(conditions: $conditions, relations: $relations, query: $q);
    }
}
