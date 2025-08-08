<?php

namespace App\Traits;

use Modules\Crm\Services\CrmService;
use Modules\Media\Services\MediaService;
use Modules\Post\Repositories\PostRepository;
use Modules\User\Repositories\UserRepository;
use Modules\Media\Repositories\MediaRepository;
use Modules\User\Repositories\UserOtpRepository;
use Modules\Coin\Services\CoinTransactionService;
use Modules\Reward\Services\ProfileRewardService;
use Modules\User\Repositories\UserInfoRepository;
use Modules\Reward\Repositories\ProfileFieldRepository;
use Modules\Coin\Repositories\CoinTransactionRepository;
use Modules\Reward\Repositories\RewardProfileRepository;
use Modules\Reward\Repositories\RewardUserUnlockedRepository;
use Modules\User\Services\UserInfoService;

trait ClassResolver
{
    // Repositories
    protected function getUserRepository(): UserRepository
    {
        return app(UserRepository::class);
    }
    protected function getUserOtpRepository(): UserOtpRepository
    {
        return app(UserOtpRepository::class);
    }
    protected function getUserInfoRepository(): UserInfoRepository
    {
        return app(UserInfoRepository::class);
    }
    protected function getRewardProfileRepository(): RewardProfileRepository
    {
        return app(RewardProfileRepository::class);
    }
    protected function getProfileFieldRepository(): ProfileFieldRepository
    {
        return app(ProfileFieldRepository::class);
    }
    protected function getRewardUserUnlockedRepository(): RewardUserUnlockedRepository
    {
        return app(RewardUserUnlockedRepository::class);
    }
    protected function getCoinTransactionRepository(): CoinTransactionRepository
    {
        return app(CoinTransactionRepository::class);
    }
    protected function getMediaRepository(): MediaRepository
    {
        return app(MediaRepository::class);
    }
    protected function getPostRepository(): PostRepository
    {
        return app(PostRepository::class);
    }
    // Services
    protected function getCrmService(): CrmService
    {
        return app(CrmService::class);
    }
    protected function getProfileRewardService(): ProfileRewardService
    {
        return app(ProfileRewardService::class);
    }
    protected function getCoinTransactionService(): CoinTransactionService
    {
        return app(CoinTransactionService::class);
    }
    protected function getMediaService(): MediaService
    {
        return app(MediaService::class);
    }
    protected function getUserInfoService(): UserInfoService
    {
        return app(UserInfoService::class);
    }
}
