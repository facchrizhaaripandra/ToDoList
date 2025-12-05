<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

echo "Categories in database:\n";
$categories = Category::all();
echo "Count: " . $categories->count() . "\n";
foreach($categories as $cat) {
    echo "- ID: {$cat->id}, Name: {$cat->name}, Color: {$cat->color}, Icon: {$cat->icon}\n";
}
