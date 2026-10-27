<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\ServiceProvider;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$app->beforeBootstrapping(RegisterProviders::class, static function (Application $app): void {
    $config = $app->make('config');
    $providers = $config->get('app.providers', []);

    if ($providers === null) {
        $providers = [];
    }

    if (!is_array($providers)) {
        throw new \UnexpectedValueException('The app.providers configuration must be an array.');
    }

    $bootstrapProvidersPath = $app->getBootstrapProvidersPath();
    $bootstrapProviders = is_file($bootstrapProvidersPath)
        ? require $bootstrapProvidersPath
        : [];

    if (!is_array($bootstrapProviders)) {
        throw new \UnexpectedValueException('The bootstrap providers file must return an array.');
    }

    $config->set('app.providers', array_values(array_unique(array_merge(
        ServiceProvider::defaultProviders()->toArray(),
        $providers,
        $bootstrapProviders,
    ))));
});

return $app;
