<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "STAGES:\n";
print_r(Schema::getColumnListing('stages'));
echo "\nACTS:\n";
print_r(Schema::getColumnListing('acts'));
