<?php
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=54321');
putenv('DB_DATABASE=bangers_test');
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "STAGES:\n";
print_r(Schema::getColumnListing('stages'));
echo "\nACTS:\n";
print_r(Schema::getColumnListing('acts'));
