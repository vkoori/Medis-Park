<?php

namespace Modules\Post\Providers;

use App\Dto\PostCreatedEvent;
use App\Dto\PostSeenEvent;
use Modules\Post\Listeners\HandlePostCreated;
use Modules\Post\Listeners\HandleUserPostSeen;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        PostSeenEvent::class => [
            HandleUserPostSeen::class
        ],
        PostCreatedEvent::class => [
            HandlePostCreated::class
        ]
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
