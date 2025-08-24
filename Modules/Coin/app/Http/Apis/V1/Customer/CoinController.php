<?php

namespace Modules\Coin\Http\Apis\V1\Customer;

use Illuminate\Support\Facades\Auth;
use App\Utils\Response\SuccessFacade;
use Modules\Coin\Services\CoinTransactionService;
use Modules\Coin\Http\Resources\V1\TransactionResource;

class CoinController
{
    public function balance(CoinTransactionService $coinService)
    {
        $balance = $coinService->getUserBalance(userId: Auth::id());

        return SuccessFacade::ok(data: ['balance' => $balance]);
    }

    public function transactions(CoinTransactionService $coinService)
    {
        $transactions = $coinService->getUserTransactions(userId: Auth::id());

        return SuccessFacade::ok(data: TransactionResource::collection($transactions));
    }
}
