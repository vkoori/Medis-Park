<?php

namespace App\Utils\Response;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Success
{
    public function ok(array|string $message = null, array|JsonResource|Collection|LengthAwarePaginator|Model $data = []): StandardResponse
    {
        return new StandardResponse(
            statusCode: Response::HTTP_OK,
            message: $message,
            data: $data
        );
    }

    public function created(array|string $message = null, array|JsonResource|Collection|LengthAwarePaginator|Model $data = []): StandardResponse
    {
        return new StandardResponse(
            statusCode: Response::HTTP_CREATED,
            message: $message,
            data: $data
        );
    }
}
