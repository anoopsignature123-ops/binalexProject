<?php

use App\Http\Controllers\User\AuthController;
use App\Models\TokenPricing;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $pricing = TokenPricing::first();
    return view('welcome', compact('pricing'));
});

// Fallback login route for authentication middleware
Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

Route::get('/sponsor-lookup/{code}', [AuthController::class, 'lookupSponsor'])->name('sponsor.lookup');

require __DIR__.'/admin.php';
require __DIR__.'/user.php';

// Global Fallback 404 handler for invalid URLs
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});