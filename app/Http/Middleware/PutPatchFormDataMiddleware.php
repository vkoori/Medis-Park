<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

class PutPatchFormDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (
            in_array($request->getMethod(), ['PUT', 'PATCH']) &&
            str_contains($request->header('Content-Type'), 'multipart/form-data')
        ) {
            $this->parseRequest($request);
        }

        return $next($request);
    }

    protected function parseRequest(Request $request)
    {
        $content = $request->getContent();
        $boundary = $this->getBoundary($request->header('Content-Type'));

        if (!$boundary) return;

        $parts = explode("--$boundary", $content);
        $files = [];
        $data = [];

        foreach ($parts as $part) {
            if (empty(trim($part)) || $part === "--\r\n") continue;

            if (preg_match('/name="([^"]+)"(?:; filename="([^"]+)")?/', $part, $matches)) {
                $name = $matches[1];
                $filename = $matches[2] ?? null;

                $contentStart = strpos($part, "\r\n\r\n") + 4;
                $content = substr($part, $contentStart, -2); // Remove trailing \r\n

                if ($filename) {
                    // Handle file upload
                    $tempPath = tempnam(sys_get_temp_dir(), 'laravel-upload');
                    file_put_contents($tempPath, $content);

                    $files[$name] = new UploadedFile(
                        $tempPath,
                        $filename,
                        mime_content_type($tempPath),
                        null,
                        true
                    );
                } else {
                    // Handle regular field
                    $data[$name] = trim($content);
                }
            }
        }

        $request->merge($data);
        $request->files = new FileBag($files);
    }

    protected function getBoundary($contentType)
    {
        preg_match('/boundary=(.*)$/', $contentType, $matches);
        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
