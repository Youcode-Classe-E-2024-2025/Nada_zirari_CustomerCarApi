<?php

namespace App\Providers;

use App\Repositories\ResponseRepository;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\ResponseService;
use App\Services\TicketService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register repositories
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make('App\Models\User'));
        });
        
        $this->app->bind(TicketRepository::class, function ($app) {
            return new TicketRepository($app->make('App\Models\Ticket'));
        });
        
        $this->app->bind(ResponseRepository::class, function ($app) {
            return new ResponseRepository($app->make('App\Models\Response'));
        });

        // Register services
        $this->app->bind(AuthService::class, function ($app) {
            return new AuthService($app->make(UserRepository::class));
        });
        
        $this->app->bind(TicketService::class, function ($app) {
            return new TicketService($app->make(TicketRepository::class));
        });
        
        $this->app->bind(ResponseService::class, function ($app) {
            return new ResponseService(
                $app->make(ResponseRepository::class),
                $app->make(TicketRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
