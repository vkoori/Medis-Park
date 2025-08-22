<?php

namespace Modules\Product\Http\Apis\Admin\V1;

use App\Utils\Response\SuccessFacade;
use Modules\Product\Http\Requests\V1\PostPriceRequest;
use Modules\Product\Http\Resources\PostPriceResource;
use Modules\Product\Services\PostPriceService;

class PostPriceController
{
    public function index(PostPriceService $postPriceService)
    {
        $prices = $postPriceService->getAllPostPrices();

        return SuccessFacade::ok(data: PostPriceResource::collection($prices));
    }

    public function newPrice(PostPriceRequest $request, PostPriceService $postPriceService)
    {
        $postPriceService->newPrice(coinValue: $request->validated('coin_value'));

        return SuccessFacade::ok();
    }
}
