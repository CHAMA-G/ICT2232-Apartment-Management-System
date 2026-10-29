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
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            '*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                return match ($user->role) {
                    'admin' => redirect()->route('admin.dashboard'),
                    'security' => redirect()->route('security.dashboard'),
                    'resident' => redirect()->route('resident.dashboard'),
                    default => redirect()->route('dashboard'),
                };
            }

            return redirect()->route('login')->with('status', 'Your session expired. Please log in again.');
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403) {
                if (\Illuminate\Support\Facades\Auth::check()) {
                    $user = \Illuminate\Support\Facades\Auth::user();
                    $targetRoute = match ($user->role) {
                        'resident' => 'resident.dashboard',
                        'security' => 'security.dashboard',
                        default => 'dashboard',
                    };
                    return redirect()->route($targetRoute)->with('status_error', 'Administrator access required. You are currently logged in as ' . $user->name . ' (' . $user->role . '). Please log in with an Admin account.');
                }

                return redirect()->route('login')->with('status_error', 'Please log in with an Administrator account.');
            }
        });
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
