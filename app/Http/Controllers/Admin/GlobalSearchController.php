<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // 1. Pages & Navigation Links
        $pages = [
            ['title' => 'Admin Dashboard Overview', 'url' => route('admin.dashboard'), 'icon' => 'fa-gauge'],
            ['title' => 'Manage Staking Packages', 'url' => route('admin.packages.index'), 'icon' => 'fa-box-archive'],
            ['title' => 'All System Users List', 'url' => route('admin.users.index'), 'icon' => 'fa-list-ul'],
            ['title' => 'Genealogy Team Tree', 'url' => route('admin.users.tree'), 'icon' => 'fa-diagram-project'],
            ['title' => 'USDT Deposits Management', 'url' => route('admin.deposits.index'), 'icon' => 'fa-wallet'],
            ['title' => 'Coin Withdrawals Payouts', 'url' => route('admin.withdrawals.index'), 'icon' => 'fa-hand-holding-dollar'],
            ['title' => 'Support Tickets Desk', 'url' => route('admin.support.index'), 'icon' => 'fa-headset'],
            ['title' => 'System Token Price & Pool Settings', 'url' => route('admin.settings.index'), 'icon' => 'fa-sliders'],
            ['title' => 'All Transactions Report', 'url' => route('admin.reports.transactions'), 'icon' => 'fa-receipt'],
            ['title' => 'Daily ROI Income Report', 'url' => route('admin.reports.roi'), 'icon' => 'fa-coins'],
            ['title' => 'Direct Income Report', 'url' => route('admin.reports.direct'), 'icon' => 'fa-bolt'],
            ['title' => 'Level Income Report', 'url' => route('admin.reports.level'), 'icon' => 'fa-layer-group'],
            ['title' => 'Reward Income Report', 'url' => route('admin.reports.rewards'), 'icon' => 'fa-trophy'],
        ];

        foreach ($pages as $page) {
            if (stripos($page['title'], $q) !== false) {
                $results[] = [
                    'category' => 'Navigation Page',
                    'title' => $page['title'],
                    'subtitle' => 'Quick Admin Menu Link',
                    'url' => $page['url'],
                    'icon' => $page['icon'],
                ];
            }
        }

        // 2. Users Search
        $users = User::where(function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('referral_code', 'like', "%{$q}%")
                  ->orWhere('mobile', 'like', "%{$q}%");
        })->take(5)->get();

        foreach ($users as $user) {
            $results[] = [
                'category' => 'User Account',
                'title' => $user->name . " ({$user->referral_code})",
                'subtitle' => "Email: {$user->email} | Status: " . strtoupper($user->status),
                'url' => route('admin.users.show', $user->id),
                'icon' => 'fa-user',
            ];
        }

        // 3. Support Tickets Search
        $tickets = SupportTicket::with('user')->where(function ($query) use ($q) {
            $query->where('ticket_number', 'like', "%{$q}%")
                  ->orWhere('subject', 'like', "%{$q}%");
        })->take(4)->get();

        foreach ($tickets as $ticket) {
            $results[] = [
                'category' => 'Support Ticket',
                'title' => "Ticket #{$ticket->ticket_number}: {$ticket->subject}",
                'subtitle' => "Member: " . ($ticket->user->name ?? 'User') . " | Status: " . strtoupper($ticket->status),
                'url' => route('admin.support.show', $ticket->id),
                'icon' => 'fa-headset',
            ];
        }

        // 4. Deposits Search
        $deposits = Deposit::with('user')->where(function ($query) use ($q) {
            $query->where('transaction_id', 'like', "%{$q}%")
                  ->orWhere('amount', 'like', "%{$q}%");
        })->take(4)->get();

        foreach ($deposits as $dep) {
            $results[] = [
                'category' => 'USDT Deposit',
                'title' => "Deposit \${$dep->amount} USD (" . ($dep->user->name ?? 'User') . ")",
                'subtitle' => "Tx ID: " . ($dep->transaction_id ?? 'N/A') . " | Status: " . strtoupper($dep->status),
                'url' => route('admin.deposits.index'),
                'icon' => 'fa-wallet',
            ];
        }

        // 5. Withdrawals Search
        $withdrawals = Withdrawal::with('user')->where(function ($query) use ($q) {
            $query->where('wallet_address', 'like', "%{$q}%")
                  ->orWhere('coins', 'like', "%{$q}%");
        })->take(4)->get();

        foreach ($withdrawals as $w) {
            $results[] = [
                'category' => 'Coin Withdrawal',
                'title' => "Withdrawal {$w->coins} Coins (" . ($w->user->name ?? 'User') . ")",
                'subtitle' => "Address: " . substr($w->wallet_address ?? '', 0, 15) . "... | Status: " . strtoupper($w->status),
                'url' => route('admin.withdrawals.index'),
                'icon' => 'fa-hand-holding-dollar',
            ];
        }

        return response()->json(['results' => $results]);
    }
}
