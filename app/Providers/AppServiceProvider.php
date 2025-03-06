<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Offer;
use App\Observers\OfferObserver;
use App\Interfaces\OfferRepositoryInterface;
use App\Repositories\OfferRepository;
use App\Interfaces\OfferServiceInterface;
use App\Services\OfferService;
use App\Interfaces\ResourceDefinitionServiceInterface;
use App\Services\ResourceDefinitionService;
use App\Interfaces\ResourceDefinitionRepositoryInterface;
use App\Repositories\ResourceDefinitionRepository;
use App\Interfaces\AgencyRepositoryInterface;
use App\Repositories\AgencyRepository;
use App\Interfaces\AgencyServiceInterface;
use App\Services\AgencyService;
use App\Interfaces\CompanyRepositoryInterface;
use App\Repositories\CompanyRepository;
use App\Interfaces\CompanyServiceInterface;
use App\Services\CompanyService;
use App\Interfaces\StatusRepositoryInterface;
use App\Repositories\StatusRepository;
use App\Interfaces\StatusServiceInterface;
use App\Services\StatusService;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\UserServiceInterface;
use App\Services\UserService;
use App\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Interfaces\RoleServiceInterface;
use App\Services\RoleService;
use App\Interfaces\PermissionRepositoryInterface;
use App\Repositories\PermissionRepository;
use App\Interfaces\PermissionServiceInterface;
use App\Services\PermissionService;
use App\Interfaces\SettingRepositoryInterface;
use App\Repositories\SettingRepository;
use App\Interfaces\SettingServiceInterface;
use App\Services\SettingService;
use App\Interfaces\ActivityRepositoryInterface;
use App\Repositories\ActivityRepository;
use App\Interfaces\ActivityServiceInterface;
use App\Services\ActivityService;
use App\Interfaces\LogRepositoryInterface;
use App\Repositories\LogRepository;
use App\Interfaces\LogServiceInterface;
use App\Services\LogService;
use App\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Interfaces\NotificationServiceInterface;
use App\Services\NotificationService;
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Interfaces\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Interfaces\CommentRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Interfaces\CommentServiceInterface;
use App\Services\CommentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
        
        // Resource Definition bindings
        $this->app->bind(ResourceDefinitionServiceInterface::class, ResourceDefinitionService::class);
        $this->app->bind(ResourceDefinitionRepositoryInterface::class, ResourceDefinitionRepository::class);
        
        // Offer bindings
        $this->app->bind(OfferServiceInterface::class, OfferService::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);

        // Agency bindings
        $this->app->bind(AgencyServiceInterface::class, AgencyService::class);
        $this->app->bind(AgencyRepositoryInterface::class, AgencyRepository::class);

        // Company bindings
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);

        // Status bindings
        $this->app->bind(StatusServiceInterface::class, StatusService::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);

        // User bindings
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Role bindings
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // Permission bindings
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Setting bindings
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);

        // Activity bindings
        $this->app->bind(ActivityServiceInterface::class, ActivityService::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);

        // Log bindings
        $this->app->bind(LogServiceInterface::class, LogService::class);
        $this->app->bind(LogRepositoryInterface::class, LogRepository::class);

        // Notification bindings
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);

        // Category bindings
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // Comment bindings
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Offer::observe(OfferObserver::class);

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
