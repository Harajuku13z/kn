<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
echo "DB_CONNECTION: " . env('DB_CONNECTION') . PHP_EOL;
echo "DB_HOST: " . env('DB_HOST') . PHP_EOL;
echo "DB_PORT: " . env('DB_PORT') . PHP_EOL;
echo "DB_DATABASE: " . env('DB_DATABASE') . PHP_EOL;
echo "DB_USERNAME: " . env('DB_USERNAME') . PHP_EOL;
echo "DB_PASSWORD: " . env('DB_PASSWORD') . PHP_EOL;
echo "DB_SOCKET: " . env('DB_SOCKET') . PHP_EOL; 