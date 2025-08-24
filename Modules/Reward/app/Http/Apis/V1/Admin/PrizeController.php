<?php

namespace Modules\Reward\Http\Apis\V1\Admin;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Reward\Services\PrizeService;
use Modules\Reward\Http\Resources\V1\PrizeResource;
use Modules\Reward\Http\Requests\V1\PrizeCoinRequest;

class PrizeController
{
    public function addProduct(
        int $jYear,
        int $jMonth,
        int $productId,
        PrizeService $prizeService
    ) {
        $prizeService->addProduct(
            jYear: $jYear,
            jMonth: $jMonth,
            productId: $productId,
            createdBy: Auth::id()
        );

        return SuccessFacade::ok();
    }

    public function addCoin(
        int $jYear,
        int $jMonth,
        PrizeCoinRequest $request,
        PrizeService $prizeService
    ) {
        $prizeService->addCoin(
            jYear: $jYear,
            jMonth: $jMonth,
            coinAmount: $request->validated('coin_amount'),
            createdBy: Auth::id()
        );

        return SuccessFacade::ok();
    }

    public function index(PrizeService $prizeService)
    {
        $prizes = $prizeService->paginate();

        return SuccessFacade::ok(data: PrizeResource::collection($prizes));
    }
}
