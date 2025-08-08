<?php

namespace Modules\Media\Http\Apis\Admin\V1;

use App\Utils\Response\SuccessFacade;
use Modules\Media\Services\MediaService;
use Modules\Media\Enums\UploadableDiskEnum;
use Modules\Media\Http\Resources\MediaResource;
use Modules\Media\Http\Requests\V1\UploadRequest;

class MediaController
{
    public function privateDisks()
    {
        $disks = array_map(
            callback: fn(UploadableDiskEnum $disk): array => $disk->translate(),
            array: UploadableDiskEnum::private()
        );

        return SuccessFacade::ok(
            data: $disks
        );
    }

    public function allDisks()
    {
        return SuccessFacade::ok(
            data: UploadableDiskEnum::translates()
        );
    }

    public function upload(UploadRequest $request, MediaService $mediaService)
    {
        $media = $mediaService->upload(
            file: $request->validated('file'),
            disk: UploadableDiskEnum::from($request->validated('disk'))->disk()
        );

        return SuccessFacade::ok(
            data: MediaResource::make($media)
        );
    }
}
