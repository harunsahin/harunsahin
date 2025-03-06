<?php

namespace App\Providers;

use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\ActivityServiceInterface;
use App\Repositories\ActivityRepository;
use App\Services\ActivityService;
use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(ActivityServiceInterface::class, ActivityService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 