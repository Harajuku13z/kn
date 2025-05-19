<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get all articles
$articles = App\Models\Article::all();

echo "Total articles: " . $articles->count() . "\n";

// Display articles by type
$clothingArticles = App\Models\Article::where('is_active', true)
    ->where(function($query) {
        $query->whereJsonContains('type', 'clothing')
              ->orWhereJsonContains('type', 'vêtement')
              ->orWhereJsonContains('type', 'vetement');
    })
    ->orderBy('name')
    ->get();

$householdArticles = App\Models\Article::where('is_active', true)
    ->where(function($query) {
        $query->whereJsonContains('type', 'household')
              ->orWhereJsonContains('type', 'linge')
              ->orWhereJsonContains('type', 'maison');
    })
    ->orderBy('name')
    ->get();

echo "\nClothing articles: " . $clothingArticles->count() . "\n";
foreach ($clothingArticles as $article) {
    echo " - " . $article->name . " (" . $article->average_weight . " kg)\n";
}

echo "\nHousehold articles: " . $householdArticles->count() . "\n";
foreach ($householdArticles as $article) {
    echo " - " . $article->name . " (" . $article->average_weight . " kg)\n";
} 