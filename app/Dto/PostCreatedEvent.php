<?php

namespace App\Dto;

class PostCreatedEvent
{
    public function __construct(
        private int $postId,
        private string $jalaliMonth,
    ) {}

    public function getPostId()
    {
        return $this->postId;
    }

    public function getJalaliMonth()
    {
        return $this->jalaliMonth;
    }
}
