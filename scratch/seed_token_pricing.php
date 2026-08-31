<?php

use App\Models\TokenPricing;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pricing = TokenPricing::first();

if (!$pricing) {
    TokenPricing::create([
        'total_pool_usd' => 300000.00,
        'total_pool_coins' => 13000000.00,
        'coin_rate_usd' => 0.0231, // $300,000 / 13,000,000 = $0.02307 per coin
    ]);
    echo "Created default TokenPricing: 1.3 Crore BINA Coins, $300,000 USD Pool, Rate = $0.0231 USD\n";
} else {
    echo "TokenPricing already exists: USD=" . $pricing->total_pool_usd . ", Coins=" . $pricing->total_pool_coins . ", Rate=" . $pricing->coin_rate_usd . "\n";
}
