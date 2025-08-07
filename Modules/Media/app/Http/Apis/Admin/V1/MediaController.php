<?php

namespace Modules\Media\Http\Apis\Admin\V1;

use App\Utils\Response\SuccessFacade;
use Modules\Media\Http\Resources\MediaResource;
use Modules\Media\Services\MediaService;
use Modules\Media\Enums\UploadableDiskEnum;
use Modules\Media\Http\Requests\V1\UploadRequest;

class MediaController
{
    public function disks()
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
