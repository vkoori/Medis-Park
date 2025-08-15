<?php

namespace Modules\Post\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Post\Models\Post;
use Modules\User\Models\User;
use Modules\Post\Dto\PostSaveDto;
use Modules\Post\Dto\PostFilterDto;
use Modules\Post\Exceptions\PostExceptions;
use Morilog\Jalali\Jalalian;

class PostService
{
    use ClassResolver;

    public function createMonthlyPost(PostSaveDto $dto, User $user): Post
    {
        $month = $dto->getStartOfMonth();
        $maxPostsInMonth = (clone $month)
            ->addMonths(months: 1)
            ->subDay()
            ->getDay();

        $existingPostsCount = $this->getPostRepository()->count(conditions: [
            'month' => $dto->jYearMonth
        ]);

        if ($existingPostsCount >= $maxPostsInMonth) {
            throw PostExceptions::postsIsFull();
        }

        $media = $this->getMediaService()->upload(
            file: $dto->media,
            disk: $dto->disk
        );

        return $this->getPostRepository()
            ->create(attributes: [
                'media_id' => $media->id,
                'title' => $dto->title,
                'content' => $dto->content,
                'month' => $month->format('Y-m'),
                'updated_by' => $user->id,
            ])
            ->setRelations([
                'updatedBy' => $user,
                'media' => $media
            ]);
    }

    public function paginate(PostFilterDto $dto)
    {
        $monthFilter = [];
        if ($dto->hasField('month')) {
            $monthFilter['month'] = $dto->getStartOfMonth()->format('Y-m');
        }

        return $this->getPostRepository()->paginate(
            conditions: $monthFilter + $dto->getProvidedDataSnakeCase(),
            relations: ['updatedBy', 'media']
        );
    }

    public function findPost(int $postId, bool $loadModifier = false): Post
    {
        $relations = $loadModifier ? ['media', 'updatedBy'] : ['media'];
        $post = $this->getPostRepository()->findById(modelId: $postId, relations: $relations);
        if (!$post) {
            throw PostExceptions::notFound();
        }

        return $post;
    }

    public function updatePost(int $postId, PostSaveDto $dto, User $user): bool
    {
        $values = ['updated_by' => $user->id];

        if (
            $dto->hasField(propertyName: 'disk')
            && $dto->hasField(propertyName: 'media')
        ) {
            $media = $this->getMediaService()->upload(
                file: $dto->media,
                disk: $dto->disk
            );

            $values['media_id'] = $media->id;
        }

        if ($dto->hasField(propertyName: 'title')) {
            $values['title'] = $dto->title;
        }
        if ($dto->hasField(propertyName: 'content')) {
            $values['content'] = $dto->content;
        }

        return $this->getPostRepository()->batchUpdate(
            conditions: ['id' => $postId],
            values: $values
        );
    }

    public function removePost(int $postId)
    {
        $visited = $this->getUserPostVisitRepository()->count(conditions: [
            'post_id' => $postId
        ]);
        if ($visited) {
            throw PostExceptions::canNotRemoveVisitedPost();
        }

        $this->getPostRepository()->deleteById(modelId: $postId);
    }
}
