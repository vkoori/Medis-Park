<?php

namespace App\Dto;

class PostSeenEvent
{
    public function __construct(
        private int $userId,
        private int $postId
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }
}
