<?php

namespace Modules\Product\Http\Apis\Customer\V1;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Product\Services\MonthlyItemService;
use Modules\Product\Http\Resources\MonthlyItemResource;

class MonthlyItemController
{
    public function index(int $jYear, int $jMonth, MonthlyItemService $monthlyItemService)
    {
        $items = $monthlyItemService->getMonthlyItemsForCustomer(
            jYear: $jYear,
            jMonth: $jMonth,
            userId: Auth::id()
        );

        return SuccessFacade::ok(data: MonthlyItemResource::collection($items));
    }
}
