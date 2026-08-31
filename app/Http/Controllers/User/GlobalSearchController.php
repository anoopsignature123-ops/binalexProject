<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // 1. Pages & Navigation Links
        $pages = [
            ['title' => 'Dashboard Overview', 'url' => route('user.dashboard'), 'icon' => 'fa-gauge'],
            ['title' => 'Stake Packages', 'url' => route('user.staking.index'), 'icon' => 'fa-box-archive'],
            ['title' => 'Deposit USDT', 'url' => route('user.deposit.index'), 'icon' => 'fa-wallet'],
            ['title' => 'Withdraw Coins', 'url' => route('user.withdrawal.index'), 'icon' => 'fa-hand-holding-dollar'],
            ['title' => 'P2P Wallet Transfer', 'url' => route('user.p2p.index'), 'icon' => 'fa-right-left'],
            ['title' => 'My Directs Team', 'url' => route('user.network.directs'), 'icon' => 'fa-user-group'],
            ['title' => 'Genealogy Tree', 'url' => route('user.network.tree'), 'icon' => 'fa-diagram-project'],
            ['title' => 'Team Levels Structure', 'url' => route('user.reports.team'), 'icon' => 'fa-layer-group'],
            ['title' => 'Daily ROI Yield History', 'url' => route('user.reports.roi'), 'icon' => 'fa-coins'],
            ['title' => 'Direct Income History', 'url' => route('user.reports.direct'), 'icon' => 'fa-bolt'],
            ['title' => 'Level Income History', 'url' => route('user.reports.level'), 'icon' => 'fa-layer-group'],
            ['title' => 'Reward Income History', 'url' => route('user.reports.rewards'), 'icon' => 'fa-trophy'],
            ['title' => 'All Income Transactions', 'url' => route('user.reports.incomes'), 'icon' => 'fa-receipt'],
            ['title' => 'Help & Support Tickets', 'url' => route('user.support.index'), 'icon' => 'fa-headset'],
            ['title' => 'My Profile & Security', 'url' => route('user.profile.index'), 'icon' => 'fa-user-gear'],
        ];

        foreach ($pages as $page) {
            if (stripos($page['title'], $q) !== false) {
                $results[] = [
                    'category' => 'Navigation Page',
                    'title' => $page['title'],
                    'subtitle' => 'Quick Member Menu Link',
                    'url' => $page['url'],
                    'icon' => $page['icon'],
                ];
            }
        }

        // 2. My Team Directs Search
        $team = User::where('sponsor_id', $user->id)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('referral_code', 'like', "%{$q}%");
            })->take(5)->get();

        foreach ($team as $member) {
            $results[] = [
                'category' => 'Direct Team Member',
                'title' => $member->name . " ({$member->referral_code})",
                'subtitle' => "Status: " . strtoupper($member->status) . " | Email: {$member->email}",
                'url' => route('user.network.tree', $member->id),
                'icon' => 'fa-user-group',
            ];
        }

        // 3. My Support Tickets Search
        $tickets = SupportTicket::where('user_id', $user->id)
            ->where(function ($query) use ($q) {
                $query->where('ticket_number', 'like', "%{$q}%")
                      ->orWhere('subject', 'like', "%{$q}%");
            })->take(4)->get();

        foreach ($tickets as $ticket) {
            $results[] = [
                'category' => 'Support Ticket',
                'title' => "Ticket #{$ticket->ticket_number}: {$ticket->subject}",
                'subtitle' => "Status: " . strtoupper($ticket->status) . " | Date: " . $ticket->created_at->format('M d, Y'),
                'url' => route('user.support.show', $ticket->id),
                'icon' => 'fa-headset',
            ];
        }

        // 4. My Deposits Search
        $deposits = Deposit::where('user_id', $user->id)
            ->where(function ($query) use ($q) {
                $query->where('transaction_id', 'like', "%{$q}%")
                      ->orWhere('amount', 'like', "%{$q}%");
            })->take(3)->get();

        foreach ($deposits as $dep) {
            $results[] = [
                'category' => 'USDT Deposit',
                'title' => "Deposit \${$dep->amount} USD",
                'subtitle' => "Tx ID: " . ($dep->transaction_id ?? 'N/A') . " | Status: " . strtoupper($dep->status),
                'url' => route('user.deposit.index'),
                'icon' => 'fa-wallet',
            ];
        }

        // 5. My Withdrawals Search
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->where(function ($query) use ($q) {
                $query->where('wallet_address', 'like', "%{$q}%")
                      ->orWhere('coins', 'like', "%{$q}%");
            })->take(3)->get();

        foreach ($withdrawals as $w) {
            $results[] = [
                'category' => 'Coin Withdrawal',
                'title' => "Withdrawal {$w->coins} Coins",
                'subtitle' => "Address: " . substr($w->wallet_address ?? '', 0, 15) . "... | Status: " . strtoupper($w->status),
                'url' => route('user.withdrawal.index'),
                'icon' => 'fa-hand-holding-dollar',
            ];
        }

        return response()->json(['results' => $results]);
    }
}
