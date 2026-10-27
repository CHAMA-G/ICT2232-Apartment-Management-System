<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\View\ViewServiceProvider;

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
        $exceptions->report(static function (\Throwable $exception): void {
            $target = null;

            if (
                $exception instanceof \Illuminate\Contracts\Container\BindingResolutionException
                && preg_match('/Target class \[([^\]]+)\]/', $exception->getMessage(), $matches) === 1
            ) {
                $target = $matches[1];
            }

            error_log(sprintf(
                'Laravel exception=%s file=%s line=%d%s',
                get_class($exception),
                basename($exception->getFile()),
                $exception->getLine(),
                $target === null ? '' : ' target='.$target,
            ));
        });
    })->create();

$app->register(ViewServiceProvider::class);
(new ViewServiceProvider($app))->register();

return $app;
