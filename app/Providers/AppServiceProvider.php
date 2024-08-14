<?php

namespace App\Providers;

use App\Repository\ITicketRepository;
use App\Repository\TicketRepository;
use App\Services\Auth\AuthService;
use App\Services\Auth\IAuthService;
use App\Services\Ticket\ITicketService;
use App\Services\Ticket\TicketService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Ticket::class => TicketPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    public function registerServices(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(ITicketService::class, TicketService::class);
    }

    public function registerRepositories(): void
    {
        $this->app->bind(ITicketRepository::class, TicketRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
