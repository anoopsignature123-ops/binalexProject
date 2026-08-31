<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = SupportTicket::with('user')->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'open', 'replied', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('admin.support.index', compact('tickets', 'status', 'search'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'admin_reply' => 'required|string|min:3',
            'status' => 'required|string|in:replied,resolved,closed,pending',
        ]);

        $ticket->update([
            'admin_reply' => $request->input('admin_reply'),
            'status' => $request->input('status'),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.support.show', $ticket->id)
            ->with('success', "Reply posted successfully for Ticket #{$ticket->ticket_number}. Status set to " . strtoupper($ticket->status) . ".");
    }
}
