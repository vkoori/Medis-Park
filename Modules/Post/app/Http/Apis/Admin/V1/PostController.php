<?php

namespace Modules\Post\Http\Apis\Admin\V1;

use Modules\Post\Dto\PostSaveDto;
use App\Utils\Response\ErrorFacade;
use Modules\Post\Dto\PostFilterDto;
use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Post\Services\PostService;
use Modules\Post\Http\Resources\PostAdminResource;
use Modules\Post\Http\Requests\V1\PostSaveRequest;
use Modules\Post\Http\Requests\V1\PostFilterRequest;

class PostController
{
    public function store(PostSaveRequest $request, PostService $postService)
    {
        $dto = PostSaveDto::fromFormRequest(request: $request);
        $post = $postService->createMonthlyPost(dto: $dto, user: Auth::user());

        return SuccessFacade::ok(
            data: PostAdminResource::make($post)
        );
    }

    public function index(PostFilterRequest $request, PostService $postService)
    {
        $dto = PostFilterDto::fromFormRequest($request);
        $posts = $postService->paginate(dto: $dto);

        return SuccessFacade::ok(
            data: PostAdminResource::collection($posts)
        );
    }

    public function show(int $postId, PostService $postService)
    {
        $post = $postService->findPost(postId: $postId, loadModifier: true);

        return SuccessFacade::ok(
            data: PostAdminResource::make($post)
        );
    }

    public function update(int $postId, PostSaveRequest $request, PostService $postService)
    {
        $dto = PostSaveDto::fromFormRequest(request: $request);
        $updated = $postService->updatePost(postId: $postId, dto: $dto, user: Auth::user());

        return $updated
            ? SuccessFacade::ok()
            : ErrorFacade::badRequest();
    }

    public function destroy(int $postId, PostService $postService)
    {
        $postService->removePost(postId: $postId);

        return SuccessFacade::ok();
    }
}
