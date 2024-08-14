<?php

use App\Exceptions\Error;
use App\Exceptions\Handler;
use App\Http\Middleware\ApiGuardMiddleware;
use App\Http\Middleware\SanctumAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
//        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
//        commands: __DIR__.'/../routes/console.php',
//        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            "ApiGuardMiddleware" => ApiGuardMiddleware::class,
            'throttle:api',
            SubstituteBindings::class,
            SanctumAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })
    ->create();
