<?php

namespace Modules\Post\Services;

use App\Traits\ClassResolver;
use Modules\Post\Models\Post;
use Modules\User\Models\User;
use Modules\Post\Dto\PostSaveDto;
use Modules\Post\Dto\PostFilterDto;
use Modules\Post\Exceptions\PostExceptions;

class PostService
{
    use ClassResolver;

    public function createPost(PostSaveDto $dto, User $user): Post
    {
        $media = $this->getMediaService()->upload(
            file: $dto->banner,
            disk: $dto->disk
        );

        return $this->getPostRepository()
            ->create(attributes: [
                'banner' => $media->id,
                'title' => $dto->title,
                'content' => $dto->content,
                'available_at' => $dto->availableAt,
                'expired_at' => $dto->expiredAt,
                'updated_by' => $user->id,
            ])
            ->setRelations([
                'updatedBy' => $user,
                'media' => $media
            ]);
    }

    public function paginate(PostFilterDto $dto)
    {
        return $this->getPostRepository()->paginate(
            conditions: $dto->getProvidedDataSnakeCase(),
            relations: ['updatedBy', 'media']
        );
    }

    public function findPost(int $postId, bool $loadModifier = false): Post
    {
        $post = $this->getPostRepository()->findById(modelId: $postId, relations: ['media', 'updatedBy']);
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
            && $dto->hasField(propertyName: 'banner')
        ) {
            $media = $this->getMediaService()->upload(
                file: $dto->banner,
                disk: $dto->disk
            );

            $values['banner'] = $media->id;
        }

        if ($dto->hasField(propertyName: 'title')) {
            $values['title'] = $media->title;
        }
        if ($dto->hasField(propertyName: 'content')) {
            $values['content'] = $media->content;
        }
        if ($dto->hasField(propertyName: 'available_at')) {
            $values['available_at'] = $media->available_at;
        }
        if ($dto->hasField(propertyName: 'expired_at')) {
            $values['expired_at'] = $media->available_at;
        }

        return $this->getPostRepository()->batchUpdate(
            conditions: ['id' => $postId],
            values: $values
        );
    }
}
