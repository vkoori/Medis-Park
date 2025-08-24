<?php

namespace Modules\Post\Services;

use App\Dto\PostSeenEvent;
use Morilog\Jalali\Jalalian;
use App\Traits\ClassResolver;
use Modules\Post\Models\Post;
use Illuminate\Support\Facades\DB;
use Modules\Post\Enums\UserPostVisitEnum;
use Modules\Post\Exceptions\PostVisitExceptions;

class PostVisitService
{
    use ClassResolver;

    public function unlockPostNormally(int $userId): Post
    {
        $post = $this->getPostRepository()->findTodayPost(
            date: Jalalian::now(),
            userId: $userId,
            relations: ['media']
        );

        if (!$post) {
            throw PostVisitExceptions::notPostForVisit();
        }

        if ($post->visited) {
            throw PostVisitExceptions::alreadySeen();
        }

        $this->getUserPostVisitRepository()->create(attributes: [
            'user_id' => $userId,
            'post_id' => $post->id,
            'type' => UserPostVisitEnum::NORMAL,
        ]);
        $post->visited = 1;

        event(new PostSeenEvent(userId: $userId, postId: $post->id));

        return $post;
    }

    public function unlockPostByCoin(int $userId, int $postId)
    {
        $post = $this->getPostRepository()->findPostWithSeen(
            postId: $postId,
            userId: $userId,
            relations: ['media']
        );

        if (!$post) {
            throw PostVisitExceptions::notPostForVisit();
        }

        if ($post->visited) {
            throw PostVisitExceptions::alreadySeen();
        }

        DB::transaction(function () use ($post, $userId) {
            $this->getOrderService()->buyPost(userId: $userId, postId: $post->id);

            $this->getUserPostVisitRepository()->create(attributes: [
                'user_id' => $userId,
                'post_id' => $post->id,
                'type' => UserPostVisitEnum::ORDER,
            ]);
            $post->visited = 1;
        });

        return $post;
    }
}
