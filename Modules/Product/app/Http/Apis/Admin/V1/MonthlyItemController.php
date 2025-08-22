<?php

namespace Modules\Product\Http\Apis\Admin\V1;

use App\Utils\Response\SuccessFacade;
use Modules\Product\Dto\MonthlyCoinDto;
use Modules\Product\Dto\MonthlyComponentDto;
use Modules\Product\Http\Requests\V1\MonthlyItemOrderingRequest;
use Modules\Product\Services\MonthlyItemService;
use Modules\Product\Http\Resources\MonthlyItemResource;
use Modules\Product\Http\Requests\V1\MonthlyCoinSaveRequest;
use Modules\Product\Http\Requests\V1\MonthlyComponentSaveRequest;

class MonthlyItemController
{
    public function addComponent(
        MonthlyComponentSaveRequest $request,
        MonthlyItemService $monthlyItemService
    ) {
        $dto = MonthlyComponentDto::fromFormRequest(request: $request);
        $monthlyItemService->addComponent(dto: $dto);

        return SuccessFacade::ok();
    }

    public function addCoin(
        MonthlyCoinSaveRequest $request,
        MonthlyItemService $monthlyItemService
    ) {
        $dto = MonthlyCoinDto::fromFormRequest(request: $request);
        $monthlyItemService->addCoin(dto: $dto);

        return SuccessFacade::ok();
    }

    public function index(int $jYear, int $jMonth, MonthlyItemService $monthlyItemService)
    {
        $items = $monthlyItemService->getMonthlyItems(jYear: $jYear, jMonth: $jMonth);

        return SuccessFacade::ok(data: MonthlyItemResource::collection($items));
    }

    public function increase(MonthlyItemOrderingRequest $request, MonthlyItemService $monthlyItemService)
    {
        $monthlyItemService->increasePriority(
            itemId: $request->validated('item_id'),
            itemType: $request->validated('type')
        );

        return SuccessFacade::ok();
    }

    public function decrease(MonthlyItemOrderingRequest $request, MonthlyItemService $monthlyItemService)
    {
        $monthlyItemService->decreasePriority(
            itemId: $request->validated('item_id'),
            itemType: $request->validated('type')
        );

        return SuccessFacade::ok();
    }
}
