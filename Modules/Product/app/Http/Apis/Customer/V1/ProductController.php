<?php

namespace Modules\Product\Http\Apis\Customer\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Product\Dto\ProductFilterDto;
use Modules\Product\Services\ProductService;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Http\Requests\V1\ProductFilterRequest;

class ProductController
{
    public function index(ProductFilterRequest $request, ProductService $productService)
    {
        $dto = ProductFilterDto::fromFormRequest($request);
        $products = $productService->paginateCustomer(dto: $dto, customerUserId: Auth::id());

        return SuccessFacade::ok(data: ProductResource::collection($products));
    }
}
