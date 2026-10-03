<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\GlobalSearchController;
use App\Http\Controllers\User\NetworkController;
use App\Http\Controllers\User\P2pTransferController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\StakingController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\User\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('user.login');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('user.register');
    Route::post('/register', [AuthController::class, 'register'])->name('user.register.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::middleware(['auth', 'role:user'])->group(function (): void {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('user.global-search');

        // Staking
        Route::get('/staking', [StakingController::class, 'index'])->name('user.staking.index');
        Route::post('/staking', [StakingController::class, 'store'])->name('user.staking.store');

        // Deposits
        Route::get('/deposit', [DepositController::class, 'index'])->name('user.deposit.index');
        Route::post('/deposit', [DepositController::class, 'store'])->name('user.deposit.store');
        Route::post('/deposit/oxapay', [DepositController::class, 'createOxapayInvoice'])->name('user.deposit.oxapay');
        Route::get('/deposit/{deposit}/payment', [DepositController::class, 'paymentView'])->name('user.deposit.payment');
        Route::get('/deposit/{deposit}/check-status', [DepositController::class, 'checkStatus'])->name('user.deposit.check-status');

        // Withdrawals (Earning Wallet)
        Route::get('/withdrawal', [WithdrawalController::class, 'index'])->name('user.withdrawal.index');
        Route::post('/withdrawal', [WithdrawalController::class, 'store'])->name('user.withdrawal.store');

        // P2P Wallet Transfer (ROI Wallet & Earning Wallet Transfer to Member)
        Route::get('/p2p-transfer', [P2pTransferController::class, 'index'])->name('user.p2p.index');
        Route::post('/p2p-transfer', [P2pTransferController::class, 'store'])->name('user.p2p.store');
        Route::get('/p2p-transfer/verify-user', [P2pTransferController::class, 'verifyUser'])->name('user.p2p.verify');

        // My Network Module (My Directs & My Total Team Tree)
        Route::get('/network/directs', [NetworkController::class, 'directs'])->name('user.network.directs');
        Route::get('/network/tree/{user?}', [NetworkController::class, 'tree'])->name('user.network.tree');

        // Reports, Income & Package Activations
        Route::get('/reports/incomes', [ReportController::class, 'incomes'])->name('user.reports.incomes');
        Route::get('/reports/direct-income', [ReportController::class, 'directIncomes'])->name('user.reports.direct');
        Route::get('/reports/level-income', [ReportController::class, 'levelIncomes'])->name('user.reports.level');
        Route::get('/reports/rewards', [ReportController::class, 'rewardIncomes'])->name('user.reports.rewards');
        Route::get('/reports/roi-income', [ReportController::class, 'roiIncomes'])->name('user.reports.roi');
        Route::get('/reports/package-activations', [ReportController::class, 'packageActivations'])->name('user.reports.packages');
        Route::get('/reports/team', [ReportController::class, 'team'])->name('user.reports.team');
        // Profile & Security Password
        Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile.index');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('user.profile.update-password');

        // Support Tickets
        Route::get('/support', [SupportTicketController::class, 'index'])->name('user.support.index');
        Route::post('/support', [SupportTicketController::class, 'store'])->name('user.support.store');
        Route::get('/support/{id}', [SupportTicketController::class, 'show'])->name('user.support.show');
    });
});
