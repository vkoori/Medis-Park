<?php

namespace Modules\Order\Http\Apis\V1\Customer;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Order\Services\OrderService;

class OrderController
{
    public function productOrder(int $productId, OrderService $orderService)
    {
        $orderService->buyProduct(userId: Auth::id(), productId: $productId);
        
        return SuccessFacade::ok();
    }
}
