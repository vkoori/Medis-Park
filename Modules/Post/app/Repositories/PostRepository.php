<?php

namespace Modules\Post\Repositories;

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
