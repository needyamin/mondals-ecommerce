<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$tm = app(\App\Services\ThemeManager::class);
$path = $tm->getViewsPath();
echo "Active Theme: " . $tm->getActive() . "\n";
print_r(Illuminate\Support\Facades\View::getFinder()->getPaths());
