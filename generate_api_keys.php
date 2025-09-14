<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Application;

echo "API Key生成を開始します...\n";

$applications = Application::whereNull('api_key')->get();

if ($applications->isEmpty()) {
    echo "API Keyが未設定のApplicationはありません。\n";
    exit;
}

foreach ($applications as $app) {
    $apiKey = $app->generateApiKey();
    echo "Generated API key for: {$app->name} (ID: {$app->id})\n";
    echo "API Key: {$apiKey}\n";
    echo "---\n";
}

echo "完了しました！\n";
