<?php

$dbPath = '/tmp/database.sqlite';

if (! file_exists($dbPath)) {
    touch($dbPath);
}

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE='.$dbPath);
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $dbPath;

$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

require __DIR__.'/../public/index.php';
