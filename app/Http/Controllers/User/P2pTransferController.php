<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\P2pTransfer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class P2pTransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transfers = P2pTransfer::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate(15);

        return view('user.network.p2p', compact('user', 'transfers'));
    }

    public function verifyUser(Request $request)
    {
        $code = trim($request->query('code'));
        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Please enter a referral code.']);
        }

        $recipient = User::where('referral_code', $code)
            ->orWhere('email', $code)
            ->first();

        if (!$recipient) {
            return response()->json(['success' => false, 'message' => 'Recipient member not found.']);
        }

        if ($recipient->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot transfer coins to yourself.']);
        }

        return response()->json([
            'success' => true,
            'name' => $recipient->name,
            'email' => $recipient->email,
            'code' => $recipient->referral_code,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_code' => 'required|string',
            'wallet_type' => 'required|in:roi,earning',
            'coins_amount' => 'required|numeric|min:1',
        ]);

        /** @var \App\Models\User $sender */
        $sender = Auth::user();
        $recipientCode = trim($request->input('recipient_code'));
        $walletType = $request->input('wallet_type');
        $amount = (float) $request->input('coins_amount');

        $receiver = User::where('referral_code', $recipientCode)
            ->orWhere('email', $recipientCode)
            ->first();

        if (!$receiver) {
            return back()->withErrors(['recipient_code' => 'Recipient member not found with this code/email.']);
        }

        if ($receiver->id === $sender->id) {
            return back()->withErrors(['recipient_code' => 'You cannot transfer coins to your own account.']);
        }

        if ($walletType === 'roi') {
            if ((float) $sender->coin_balance < $amount) {
                return back()->withErrors(['coins_amount' => 'Insufficient ROI Wallet balance for transfer. Available: ' . number_format($sender->coin_balance, 2) . ' Coins.']);
            }
        } else {
            if ((float) $sender->working_coin_balance < $amount) {
                return back()->withErrors(['coins_amount' => 'Insufficient Earning Wallet balance for transfer. Available: ' . number_format($sender->working_coin_balance, 2) . ' Coins.']);
            }
        }

        DB::transaction(function () use ($sender, $receiver, $walletType, $amount) {
            if ($walletType === 'roi') {
                $sender->coin_balance -= $amount;
                $receiver->coin_balance += $amount;
            } else {
                $sender->working_coin_balance -= $amount;
                $receiver->working_coin_balance += $amount;
            }

            $sender->save();
            $receiver->save();

            P2pTransfer::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'wallet_type' => $walletType,
                'coins_amount' => $amount,
                'status' => 'completed',
            ]);
        });

        return redirect()->route('user.p2p.index')
            ->with('success', 'P2P Transfer of ' . number_format($amount, 2) . ' Coins to ' . $receiver->name . ' (' . $receiver->referral_code . ') completed successfully!');
    }
}
