<?php

namespace Modules\Order\Http\Apis\V1\Admin;

use App\Utils\Response\ErrorFacade;
use App\Utils\Response\SuccessFacade;
use Modules\Order\Dto\OrderFilterDto;
use Modules\Order\Http\Requests\V1\OrderFilterRequest;
use Modules\Order\Http\Resources\OrderResource;
use Modules\Order\Services\OrderService;

class OrderController
{
    public function index(OrderFilterRequest $request, OrderService $orderService)
    {
        $dto = OrderFilterDto::fromFormRequest(request: $request);
        $orders = $orderService->paginate(dto: $dto);

        return SuccessFacade::ok(data: OrderResource::collection($orders));
    }

    public function used(int $orderId, OrderService $orderService)
    {
        $result = $orderService->used(orderId: $orderId);

        return $result
            ? SuccessFacade::ok()
            : ErrorFacade::badRequest();
    }
}
