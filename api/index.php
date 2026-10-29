<?php

$dbPath = '/tmp/database.sqlite';
$seedPath = __DIR__ . '/../database/seed.sqlite';

if (!file_exists($dbPath)) {
    if (file_exists($seedPath)) {
        copy($seedPath, $dbPath);
        chmod($dbPath, 0666);
    } else {
        touch($dbPath);
        chmod($dbPath, 0666);
    }
}

// Ensure SQLite on Vercel is used
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $dbPath);
putenv('DB_HOST=');
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $dbPath;
$_ENV['DB_HOST'] = '';
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $dbPath;
$_SERVER['DB_HOST'] = '';

require __DIR__ . '/../public/index.php';
