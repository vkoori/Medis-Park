<?php

namespace Modules\Media\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\ClassResolver;
use Modules\Media\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Exceptions\MediaExceptions;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class MediaService
{
    use ClassResolver;

    public function upload(
        UploadedFile|string $file,
        ?string $disk = null,
        ?string $path = null,
        ?string $name = null
    ): Media {
        if (!$disk) {
            $disk = config('filesystems.default');
        }

        $file = match (true) {
            is_string($file) => $this->fileToObject(path: $file),
            $file instanceof UploadedFile => $file,
        };

        $storage = Storage::disk(name: $disk);
        $filePath = $this->getUniqueFilePath(
            storage: $storage,
            dir: is_null($path) ? Carbon::now()->format('Y/m') . '/' : rtrim($path, '/') . '/',
            file: $file
        );

        $filePath = $storage->putFileAs(
            path: dirname($filePath),
            file: $file,
            name: basename($filePath)
        );

        if ($filePath === false) {
            throw MediaExceptions::uploadFailed();
        }

        $dimensions = $this->getImageDimensions(file: $file);

        return $this->getMediaRepository()->create(
            attributes: [
                'disk' => $disk,
                'bucket' => $this->getBucket(disk: $disk),
                'path' => $filePath,
                'name' => is_null($name) ? basename($filePath) : $name,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'width' => $dimensions['width'] ?? null,
                'height' => $dimensions['height'] ?? null,
                'is_private' => $this->isPrivate(disk: $disk),
            ]
        );
    }

    public function uploadRaw(
        string $fileContent,
        ?string $disk = null,
        ?string $path = null,
        ?string $name = null
    ): Media {
        if (!$disk) {
            $disk = config('filesystems.default');
        }

        $decoded = $this->decodeFileContent(
            fileContent: $fileContent,
            extension: $name ? pathinfo($name, PATHINFO_EXTENSION) : 'bin'
        );
        $fileName = $name ? basename($name) : Str::random();
        $fileName .= $decoded['ext'] != '' ? ".{$decoded['ext']}" : '';

        $storage = Storage::disk(name: $disk);

        $filePath = $this->getUniqueFilePath(
            storage: $storage,
            dir: is_null($path) ? Carbon::now()->format('Y/m') . '/' : rtrim($path, '/') . '/',
            file: $fileName
        );

        $uploaded = $storage->put(
            path: $filePath,
            contents: $decoded['data'],
            options: [
                'ContentType' => $decoded['mime']
            ]
        );

        if ($uploaded === false) {
            throw MediaExceptions::uploadFailed();
        }

        $dimensions = $this->getImageDimensions(file: $fileContent);

        return $this->getMediaRepository()->create(
            attributes: [
                'disk' => $disk ?? config('filesystems.default'),
                'bucket' => $this->getBucket(disk: $disk),
                'path' => $filePath,
                'name' => is_null($name) ? basename($filePath) : $name,
                'original_name' => $fileName,
                'mime' => $decoded['mime'],
                'ext' => $decoded['ext'],
                'size' => strlen($fileContent),
                'width' => $dimensions['width'] ?? null,
                'height' => $dimensions['height'] ?? null,
                'is_private' => $this->isPrivate(disk: $disk),
            ]
        );
    }

    public function getMediaUrl(int $id)
    {
        $media = $this->getMediaRepository()->findById(modelId: $id);
        if (!$media) {
            throw MediaExceptions::fileNotFound();
        }

        return $media->path;
    }

    protected function getUniqueFilePath(
        Filesystem $storage,
        string $dir,
        SymfonyUploadedFile|string $file
    ) {
        if ($file instanceof UploadedFile) {
            $fileName = $file->getClientOriginalName();
            $fileExt = $file->getClientOriginalExtension();
        } else {
            $fileName = basename($file);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        }
        $fileNameWithoutExtension = Str::slug(
            str_replace('.', '-', pathinfo($fileName, PATHINFO_FILENAME)),
            '-',
            null
        );
        $fileName = $fileNameWithoutExtension . '.' . $fileExt;
        $fileName = rtrim($fileName, '.');
        $index = 0;

        while ($storage->exists(path: $dir . $fileName)) {
            $index++;
            $fileName = $fileNameWithoutExtension . '-' . $index . '.' . $fileExt;
            $fileName = rtrim($fileName, '.');
        }

        return $dir . $fileName;
    }

    protected function fileToObject(string $path): UploadedFile
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $path = $this->downloadFromUrl($path);
        }

        if (!File::exists($path)) {
            throw MediaExceptions::fileNotFound();
        }

        return new UploadedFile(
            path: $path,
            originalName: File::basename($path),
            mimeType: File::mimeType($path)
        );
    }

    protected function downloadFromUrl(string $url): string
    {
        $response = Http::get($url);

        if ($response->failed()) {
            throw MediaExceptions::fileNotFound();
        }

        $tempPath = sys_get_temp_dir() . '/' . Str::uuid() . '/' . basename($url);

        File::makeDirectory(dirname($tempPath), 0755, true);
        File::put($tempPath, $response->body());

        return $tempPath;
    }

    protected function isPrivate(string $disk): bool
    {
        return (config('filesystems.disks.' . $disk)['visibility'] ?? 'private') == 'private';
    }

    protected function getBucket(string $disk): string
    {
        return config('filesystems.disks.' . $disk)['bucket'] ?? '';
    }

    protected function getImageDimensions(UploadedFile|string $file): array
    {
        if ($file instanceof UploadedFile && strpos($file->getMimeType(), 'image/') === 0) {
            [$width, $height] = getimagesize($file->getPathname());
            return ['width' => $width, 'height' => $height];
        } elseif (is_string($file) && strpos($file, 'data:image/') === 0) {
            [$width, $height] = getimagesize($file);
            return ['width' => $width, 'height' => $height];
        }

        return ['width' => null, 'height' => null];
    }

    protected function detectFileName(UploadedFile|string $file)
    {
        return match (true) {
            $file instanceof UploadedFile => $file->getClientOriginalName(),
            filter_var($file, FILTER_VALIDATE_URL) || File::exists($file) => File::basename($file),
            default => Str::uuid() . '.' . $this->detectExtensionUsingFileContent(content: $file),
        };
    }

    protected function detectExtensionUsingFileContent(string $content)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'file_');
        file_put_contents($tempFile, $content);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tempFile);

        finfo_close($finfo);

        unlink($tempFile);

        $fileExtension = 'unknown';

        if (strpos($mimeType, 'image/') === 0) {
            $fileExtension = substr($mimeType, 6); // Get the part after 'image/'
        } elseif ($mimeType === 'text/plain') {
            $fileExtension = 'txt';
        } elseif ($mimeType === 'application/pdf') {
            $fileExtension = 'pdf';
        } elseif ($mimeType === 'application/zip') {
            $fileExtension = 'zip';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            $fileExtension = substr($mimeType, 6); // Get the part after 'audio/'
        } elseif (strpos($mimeType, 'video/') === 0) {
            $fileExtension = substr($mimeType, 6); // Get the part after 'video/'
        }

        return $fileExtension;
    }

    private function decodeFileContent(string $fileContent, string $extension = ''): array
    {
        $mimeType = 'application/octet-stream';
        $data = $fileContent;

        if (preg_match('/^data:(.+);base64,(.*)$/', $fileContent, $matches)) {
            $data = base64_decode($matches[2]);
            $mimeType = $matches[1];
            $extension = $this->mimeToExtension($mimeType);
        } else {
            $binaryData = base64_decode($fileContent);
            if ($binaryData !== false) {
                $data = $binaryData;
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($binaryData) ?: $mimeType;
                $extension = $this->mimeToExtension($mimeType) ?: $extension;
            }
        }

        return [
            'data' => $data,
            'mime' => $mimeType,
            'ext'  => $extension,
        ];
    }

    private function mimeToExtension(string $mimeType): string
    {
        $extension = substr($mimeType, strpos($mimeType, '/') + 1);
        $extension = match ($extension) {
            'octet-stream' => 'bin',
            'vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'msword' => 'doc',
            'vnd.ms-excel' => 'xls',
            'vnd.ms-powerpoint' => 'ppt',
            'x-zip-compressed' => 'zip',
            'x-rar-compressed' => 'rar',
            'x-7z-compressed' => '7z',
            'jpeg', 'pjpeg' => 'jpg',
            'svg+xml' => 'svg',
            'plain' => 'txt',
            'x-yaml' => 'yaml',
            'x-php' => 'php',
            'javascript' => 'js',
            'x-shockwave-flash' => 'swf',
            'x-msvideo' => 'avi',
            'quicktime' => 'mov',
            'x-matroska' => 'mkv',
            'x-ms-wmv' => 'wmv',
            'x-flac' => 'flac',
            'x-wav' => 'wav',
            'mpeg3', 'x-mpeg3' => 'mp3',
            'x-aac' => 'aac',
            default => $extension
        };

        return $extension;
    }
}
