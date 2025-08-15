<?php

namespace Modules\Post\Exceptions;

use App\Exceptions\HttpException;

class PostExceptions
{
    public static function notFound()
    {
        return new HttpException(
            statusCode: 404,
            messageBag: __('post::exceptions.post_not_found')
        );
    }

    public static function postsIsFull()
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('post::exceptions.posts_is_full')
        );
    }

    public static function canNotRemoveVisitedPost()
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('post::exceptions.can_not_remove_visited_post')
        );
    }
}
