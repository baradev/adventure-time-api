<?php

// Bootstrap script to set up SQLite database for Vercel
$dbPath = '/tmp/database.sqlite';

// Always ensure database is set up (since /tmp is ephemeral)
if (!file_exists($dbPath)) {
    // Copy the pre-seeded database from the project
    $sourceDb = __DIR__ . '/../database/database.sqlite';

    if (file_exists($sourceDb) && filesize($sourceDb) > 0) {
        // Use pre-seeded database
        copy($sourceDb, $dbPath);
        chmod($dbPath, 0664);
    } else {
        // Create and seed database dynamically
        touch($dbPath);
        chmod($dbPath, 0664);

        // Bootstrap Laravel
        $basePath = __DIR__ . '/..';
        require $basePath . '/vendor/autoload.php';

        $app = require_once $basePath . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

        // Run migrations
        $kernel->call('migrate', ['--force' => true, '--seed' => true]);
    }
}

// Forward to Laravel
require __DIR__ . '/index.php';
