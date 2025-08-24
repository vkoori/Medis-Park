<?php

namespace Modules\Reward\Http\Apis\V1\Customer;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Reward\Services\PrizeService;
use Modules\Reward\Http\Resources\V1\PrizeResource;

class PrizeController
{
    public function index(PrizeService $prizeService)
    {
        $prizes = $prizeService->paginateForCustomer(userId: Auth::id());

        return SuccessFacade::ok(data: PrizeResource::collection($prizes));
    }

    public function unlock(PrizeService $prizeService)
    {
        $prizeService->unlockPrizeByCustomer(userId: Auth::id());

        return SuccessFacade::ok();
    }
}
