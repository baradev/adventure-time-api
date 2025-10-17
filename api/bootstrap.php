<?php

// Bootstrap script to set up SQLite database for Vercel
$dbPath = '/tmp/database.sqlite';

// Create database if it doesn't exist
if (!file_exists($dbPath)) {
    // Copy the pre-seeded database from the project
    $sourceDb = __DIR__ . '/../database/database.sqlite';

    if (file_exists($sourceDb)) {
        copy($sourceDb, $dbPath);
    } else {
        // Create empty database if source doesn't exist
        touch($dbPath);

        // Run migrations and seeders
        $basePath = __DIR__ . '/..';
        require $basePath . '/vendor/autoload.php';

        $app = require_once $basePath . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

        // Run migrations
        $kernel->call('migrate', ['--force' => true]);

        // Run seeders
        $kernel->call('db:seed', ['--force' => true]);
    }
}

// Forward to Laravel
require __DIR__ . '/index.php';
