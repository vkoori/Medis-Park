<?php

namespace App\Utils\Response;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StandardResponse extends JsonResponse
{
    public function __construct(
        int $statusCode = 200,
        array|string $message = null,
        array|string|JsonResource|Collection|Model|LengthAwarePaginator $data = [],
        ?array $debug = null
    ) {
        $failed = $statusCode >= 400 || $statusCode < 200 ? true : false;
        $data = $failed ? $data : $this->render($data);
        $data = $failed && count($data) == 0 && !is_null($message) ? ['message' => $message] : $data;

        parent::__construct(
            data: [
                'status' => $failed ? 'error' : 'success',
                'message' => $message ?? Response::$statusTexts[$statusCode],
                ...(
                    $failed
                    ? ['errors' => $data]
                    : ['data' => $data['res']]
                ),
                ...(
                    (!$failed && $meta = $data['with'])
                    ? ['meta' => key($meta) ? $meta : current($meta)]
                    : []
                ),
                ...(
                    (!$failed && $additional = $data['additional'])
                    ? ['additional' => key($additional) ? $additional : current($additional)]
                    : []
                ),
                'debug' => $debug
            ],
            status: $statusCode,
            headers: [
                "Content-Type" => "application/json"
            ]
        );
    }

    private function render(array|string|JsonResource|Collection|Model|LengthAwarePaginator $data = []): array
    {
        $with = [];
        $additional = [];
        $res = $data;

        if ($data instanceof LengthAwarePaginator) {
            $res = [
                'paginate' => [
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'perPage' => $data->perPage(),
                    'total' => $data->total(),
                ],
                'items' => $data->items(),
            ];
        }

        if ($data instanceof JsonResource) {
            if ($withTemp = $data->with(request())) {
                $with['labels'] = $withTemp;
            }
            if ($additionalTemp = $data->additional) {
                $additional['labels'] = $additionalTemp;
            }

            $resArray = $data->toArray(request());
            $nestedWith = [];
            $nestedAdditional = [];

            foreach ($resArray as $innerKey => $innerValue) {
                if ($innerValue instanceof JsonResource || $innerValue instanceof Collection || $innerValue instanceof Model) {
                    $nestedResult = $this->render($innerValue);
                    $nestedWith[$innerKey] = $nestedResult['with'];
                    $nestedAdditional[$innerKey] = $nestedResult['additional'];
                    $resArray[$innerKey] = $nestedResult['res'];
                }
            }

            $res = $resArray;
            if ($data->resource instanceof LengthAwarePaginator) {
                $res = [
                    'paginate' => [
                        'currentPage' => $data->resource->currentPage(),
                        'lastPage' => $data->resource->lastPage(),
                        'perPage' => $data->resource->perPage(),
                        'total' => $data->resource->total(),
                    ],
                    'items' => $resArray,
                ];
            }
            $with += $nestedWith;
            $additional += $nestedAdditional;
        }

        if (is_iterable($data)) {
            foreach ($data as $key => $value) {
                if ($value instanceof JsonResource || $value instanceof Collection || $value instanceof Model) {
                    $tmp = $value;
                    $tmp = $this->render($value);
                    $with += is_string($key) ? [$key => $tmp['with']] : $tmp['with'];
                    $additional += $tmp['additional'];
                    if(is_array($data)) {
                        $res[$key] = $tmp['res'];
                    }
                }
            }
        }

        return [
            'with' => $with,
            'additional' => $additional,
            'res' => $res,
        ];
    }
}
