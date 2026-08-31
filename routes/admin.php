<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'role:admin'])->group(function (): void {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('admin.global-search');

        // Packages Management
        Route::get('/packages', [PackageController::class, 'index'])->name('admin.packages.index');

        // Deposits Management
        Route::get('/deposits', [DepositController::class, 'index'])->name('admin.deposits.index');
        Route::put('/deposits/{deposit}', [DepositController::class, 'update'])->name('admin.deposits.update');
        Route::post('/deposits/credit', [DepositController::class, 'creditUser'])->name('admin.deposits.credit');

        // Withdrawals Management
        Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
        Route::put('/withdrawals/{withdrawal}', [WithdrawalController::class, 'update'])->name('admin.withdrawals.update');

        // User Tree, Directs, Impersonate Routes
        Route::get('/users/tree/{user?}', [UserController::class, 'tree'])->name('admin.users.tree');
        Route::get('/users/{user}/directs', [UserController::class, 'directs'])->name('admin.users.directs');
        Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('admin.users.impersonate');

        // User Management (Full CRUD)
        Route::resource('users', UserController::class)->names('admin.users');

        // Financial & Income Reports
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('admin.reports.transactions');
        Route::get('/reports/direct-income', [ReportController::class, 'directIncomes'])->name('admin.reports.direct');
        Route::get('/reports/level-income', [ReportController::class, 'levelIncomes'])->name('admin.reports.level');
        Route::get('/reports/rewards', [ReportController::class, 'rewardIncomes'])->name('admin.reports.rewards');
        Route::get('/reports/roi-income', [ReportController::class, 'roiIncomes'])->name('admin.reports.roi');
        Route::get('/reports/package-activations', [ReportController::class, 'packageActivations'])->name('admin.reports.packages');

        // System Settings & Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.update-password');
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings/pricing', [SettingController::class, 'updatePricing'])->name('admin.settings.update-pricing');

        // Support Tickets Management
        Route::get('/support', [SupportTicketController::class, 'index'])->name('admin.support.index');
        Route::get('/support/{id}', [SupportTicketController::class, 'show'])->name('admin.support.show');
        Route::post('/support/{id}/reply', [SupportTicketController::class, 'reply'])->name('admin.support.reply');
    });
});