<?php

namespace App\Traits;

use Modules\Crm\Services\CrmService;
use Modules\Media\Services\MediaService;
use Modules\Order\Services\OrderService;
use Modules\User\Services\UserInfoService;
use Modules\Post\Services\PostVisitService;
use Modules\Product\Services\ProductService;
use Modules\Post\Repositories\PostRepository;
use Modules\User\Repositories\UserRepository;
use Modules\Product\Services\PostPriceService;
use Modules\Reward\Services\PostRewardService;
use Modules\Media\Repositories\MediaRepository;
use Modules\Order\Repositories\OrderRepository;
use Modules\Reward\Repositories\PrizeRepository;
use Modules\User\Repositories\UserOtpRepository;
use Modules\Coin\Services\CoinTransactionService;
use Modules\Reward\Repositories\RewardRepository;
use Modules\Reward\Services\ProfileRewardService;
use Modules\User\Repositories\UserInfoRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Reward\Repositories\BonusTypeRepository;
use Modules\Reward\Repositories\PrizeCoinRepository;
use Modules\Product\Repositories\PostPriceRepository;
use Modules\Post\Repositories\UserPostVisitRepository;
use Modules\Reward\Repositories\PrizeUnlockRepository;
use Modules\Product\Repositories\MonthlyItemRepository;
use Modules\Reward\Repositories\ProfileFieldRepository;
use Modules\Coin\Repositories\CoinTransactionRepository;
use Modules\Product\Repositories\ProductPriceRepository;
use Modules\Product\Repositories\CoinAvailableRepository;
use Modules\Product\Repositories\ProductAvailableRepository;
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
    protected function getPostPriceRepository(): PostPriceRepository
    {
        return app(PostPriceRepository::class);
    }
    protected function getOrderRepository(): OrderRepository
    {
        return app(OrderRepository::class);
    }
    protected function getProductRepository(): ProductRepository
    {
        return app(ProductRepository::class);
    }
    protected function getProductPriceRepository(): ProductPriceRepository
    {
        return app(ProductPriceRepository::class);
    }
    protected function getPrizeRepository(): PrizeRepository
    {
        return app(PrizeRepository::class);
    }
    protected function getPrizeCoinRepository(): PrizeCoinRepository
    {
        return app(PrizeCoinRepository::class);
    }
    protected function getPrizeUnlockRepository(): PrizeUnlockRepository
    {
        return app(PrizeUnlockRepository::class);
    }
    protected function getRewardRepository(): RewardRepository
    {
        return app(RewardRepository::class);
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
    protected function getProductService(): ProductService
    {
        return app(ProductService::class);
    }
    protected function getOrderService(): OrderService
    {
        return app(OrderService::class);
    }
    protected function getPostPriceService(): PostPriceService
    {
        return app(PostPriceService::class);
    }
}
