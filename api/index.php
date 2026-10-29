<?php

$connection = getenv('DB_CONNECTION') ?: 'sqlite';
$hasExternalHost = !empty(getenv('DB_HOST')) && getenv('DB_HOST') !== '127.0.0.1';

// When running with SQLite on Vercel serverless, copy pre-seeded SQLite database to writable /tmp
if ($connection === 'sqlite' && !$hasExternalHost) {
    $dbPath = '/tmp/database.sqlite';
    $seedPath = __DIR__ . '/../database/seed.sqlite';

    if (!file_exists($dbPath)) {
        if (file_exists($seedPath)) {
            copy($seedPath, $dbPath);
        } else {
            touch($dbPath);
        }
    }

    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=' . $dbPath);
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $dbPath;
}

require __DIR__ . '/../public/index.php';
