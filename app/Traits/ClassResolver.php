<?php

namespace App\Traits;

use Modules\Crm\Services\CrmService;
use Modules\Media\Services\MediaService;
use Modules\User\Services\UserInfoService;
use Modules\Post\Services\PostVisitService;
use Modules\Post\Repositories\PostRepository;
use Modules\User\Repositories\UserRepository;
use Modules\Reward\Services\PostRewardService;
use Modules\Media\Repositories\MediaRepository;
use Modules\User\Repositories\UserOtpRepository;
use Modules\Coin\Services\CoinTransactionService;
use Modules\Reward\Services\ProfileRewardService;
use Modules\User\Repositories\UserInfoRepository;
use Modules\Post\Repositories\UserPostVisitRepository;
use Modules\Reward\Repositories\ProfileFieldRepository;
use Modules\Coin\Repositories\CoinTransactionRepository;
use Modules\Reward\Repositories\BonusTypeRepository;
use Modules\Reward\Repositories\RewardUserUnlockedRepository;

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
    protected function getBonusTypeRepository(): BonusTypeRepository
    {
        return app(BonusTypeRepository::class);
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
    protected function getUserPostVisitRepository(): UserPostVisitRepository
    {
        return app(UserPostVisitRepository::class);
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
    protected function getPostVisitService(): PostVisitService
    {
        return app(PostVisitService::class);
    }
    protected function getPostRewardService(): PostRewardService
    {
        return app(PostRewardService::class);
    }
}
