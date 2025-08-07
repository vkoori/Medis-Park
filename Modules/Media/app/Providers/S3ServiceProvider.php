<?php

namespace Modules\Media\Providers;

use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class S3ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY') && env('AWS_ENDPOINT')) {
            $config = [
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION', 'aws-global'),
                'endpoint' => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ];

            $s3 = new S3Client($config);
            $this->createBucket(
                $s3,
                env('AWS_PUBLIC_BUCKET')
            );
            $this->publicReadPolicy(
                $s3,
                env('AWS_PUBLIC_BUCKET')
            );
            $this->createBucket(
                $s3,
                env('AWS_PRIVATE_BUCKET')
            );
        }
    }

    protected function createBucket(S3Client $s3, string $bucket)
    {
        if (!$s3->doesBucketExist($bucket)) {
            $s3->createBucket(['Bucket' => $bucket]);

            $s3->waitUntil('BucketExists', ['Bucket' => $bucket]);
        }
    }

    protected function publicReadPolicy(S3Client $s3, string $bucket)
    {
        $policy = [
            "Version" => "2012-10-17",
            "Statement" => [
                [
                    "Effect" => "Allow",
                    "Principal" => "*",
                    "Action" => ["s3:GetObject"],
                    "Resource" => "arn:aws:s3:::$bucket/*"
                ]
            ]
        ];

        $s3->putBucketPolicy([
            'Bucket' => $bucket,
            'Policy' => json_encode($policy),
        ]);
    }
}
