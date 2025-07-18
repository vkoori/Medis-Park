<?php

namespace App\Utils\Response;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static StandardResponse ok(array|string $message = null, array|JsonResource|Collection|LengthAwarePaginator|Model $data = [])
 * @method static StandardResponse created(array|string $message = null, array|JsonResource|Collection|LengthAwarePaginator|Model $data = [])
 *
 * @see Success
 */
class SuccessFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'success_utils';
    }
}
