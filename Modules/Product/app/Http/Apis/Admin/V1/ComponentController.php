<?php

namespace Modules\Product\Http\Apis\Admin\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Product\Dto\ComponentFilterDto;
use Modules\Product\Dto\ComponentSaveDto;
use Modules\Product\Http\Requests\V1\ComponentFilterRequest;
use Modules\Product\Http\Requests\V1\ComponentPriceRequest;
use Modules\Product\Services\ComponentService;
use Modules\Product\Http\Resources\ComponentAdminResource;
use Modules\Product\Http\Requests\V1\ComponentSaveRequest;

class ComponentController
{
    public function store(ComponentSaveRequest $request, ComponentService $productService)
    {
        $dto = ComponentSaveDto::fromFormRequest($request);
        $product = $productService->saveProductWithPrice(
            dto: $dto,
            userId: Auth::id()
        );

        return SuccessFacade::ok(data: ComponentAdminResource::make($product));
    }

    public function index(ComponentFilterRequest $request, ComponentService $productService)
    {
        $dto = ComponentFilterDto::fromFormRequest($request);
        $products = $productService->paginate(dto: $dto);

        return SuccessFacade::ok(data: ComponentAdminResource::collection($products));
    }

    public function show(int $productId, ComponentService $productService)
    {
        $product = $productService->show(productId: $productId);

        return SuccessFacade::ok(data: ComponentAdminResource::make($product));
    }

    public function newPrice(int $productId, ComponentPriceRequest $request, ComponentService $productService)
    {
        $productService->newPrice(productId: $productId, coinValue: $request->validated('coin_value'));

        return SuccessFacade::ok();
    }
}
