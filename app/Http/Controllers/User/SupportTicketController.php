<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = SupportTicket::where('user_id', $user->id)->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'open', 'replied', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('user.support.index', compact('user', 'tickets', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|in:deposit,withdrawal,staking,account,other',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'message' => 'required|string|min:10',
        ]);

        $ticketNumber = 'TICK-' . strtoupper(substr(uniqid(), -6));

        SupportTicket::create([
            'user_id' => $user->id,
            'ticket_number' => $ticketNumber,
            'subject' => $request->input('subject'),
            'category' => $request->input('category'),
            'priority' => $request->input('priority'),
            'status' => 'pending',
            'message' => $request->input('message'),
        ]);

        return redirect()->route('user.support.index')
            ->with('success', "Support Ticket #{$ticketNumber} created successfully. Our team will respond shortly.");
    }

    public function show($id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('user_id', $user->id)->findOrFail($id);

        return view('user.support.show', compact('user', 'ticket'));
    }
}
