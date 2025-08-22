<?php

namespace Modules\Product\Http\Apis\Admin\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Product\Dto\ProductDefineDto;
use Modules\Product\Services\ProductService;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Http\Requests\V1\ProductDefineRequest;

class ProductController
{
    public function define(ProductDefineRequest $request, ProductService $productService)
    {
        $dto = ProductDefineDto::fromFormRequest($request);
        $product = $productService->saveProductWithPrice(
            dto: $dto,
            userId: Auth::id()
        );

        return SuccessFacade::ok(data: ProductResource::make($product));
    }
}
