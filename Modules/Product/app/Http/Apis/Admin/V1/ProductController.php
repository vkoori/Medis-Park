<?php

namespace Modules\Product\Http\Apis\Admin\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Product\Dto\ProductSaveDto;
use Modules\Product\Dto\ProductFilterDto;
use Modules\Product\Services\ProductService;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Http\Requests\V1\ProductSaveRequest;
use Modules\Product\Http\Requests\V1\ProductPriceRequest;
use Modules\Product\Http\Requests\V1\ProductFilterRequest;

class ProductController
{
    public function store(ProductSaveRequest $request, ProductService $productService)
    {
        $dto = ProductSaveDto::fromFormRequest($request);
        $product = $productService->saveProductWithPrice(
            dto: $dto,
            userId: Auth::id()
        );

        return SuccessFacade::ok(data: ProductResource::make($product));
    }

    public function index(ProductFilterRequest $request, ProductService $productService)
    {
        $dto = ProductFilterDto::fromFormRequest($request);
        $products = $productService->paginate(dto: $dto);

        return SuccessFacade::ok(data: ProductResource::collection($products));
    }

    public function show(int $productId, ProductService $productService)
    {
        $product = $productService->show(productId: $productId);

        return SuccessFacade::ok(data: ProductResource::make($product));
    }

    public function newPrice(int $productId, ProductPriceRequest $request, ProductService $productService)
    {
        $productService->newPrice(productId: $productId, coinValue: $request->validated('coin_value'));

        return SuccessFacade::ok();
    }
}
