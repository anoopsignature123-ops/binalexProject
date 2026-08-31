@extends('user.layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('user.support.index') }}" class="btn-gold" style="padding: 4px 12px; font-size: 0.8rem; background: rgba(255,255,255,0.08); color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Back to Support Tickets</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--gold-light);">SUPPORT TICKET #{{ $ticket->ticket_number }}</div>
            <h2 style="color: #fff; margin-top: 0.2rem;">{{ $ticket->subject }}</h2>
            <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.3rem;">
                Category: <span style="color: #fff; font-weight: 700; text-transform: uppercase;">{{ $ticket->category }}</span> | 
                Priority: <span style="color: var(--gold-light); font-weight: 700; text-transform: uppercase;">{{ $ticket->priority }}</span> | 
                Created: {{ $ticket->created_at->format('M d, Y h:i A') }}
            </div>
        </div>

        <div>
            @if($ticket->status === 'replied')
                <span class="badge-status badge-active" style="font-size: 0.9rem; padding: 6px 14px;"><i class="fa-solid fa-reply"></i> REPLIED BY ADMIN</span>
            @elseif($ticket->status === 'resolved')
                <span class="badge-status badge-active" style="font-size: 0.9rem; padding: 6px 14px; background: rgba(56,189,248,0.2); color: #38bdf8;"><i class="fa-solid fa-circle-check"></i> RESOLVED</span>
            @elseif($ticket->status === 'pending')
                <span class="badge-status badge-pending" style="font-size: 0.9rem; padding: 6px 14px;"><i class="fa-solid fa-clock"></i> AWAITING RESPONSE</span>
            @else
                <span class="badge-status badge-rejected" style="font-size: 0.9rem; padding: 6px 14px;">{{ strtoupper($ticket->status) }}</span>
            @endif
        </div>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Original Inquiry Message Card -->
    <div class="card" style="border-left: 4px solid var(--gold-primary);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
            <div class="user-profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <strong style="color: #fff;">{{ $user->name }}</strong> (You)
                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>

        <div style="background: rgba(8,10,15,0.6); padding: 1.2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); color: var(--text-main); font-size: 0.95rem; line-height: 1.6; whitespace: pre-wrap;">
            {{ $ticket->message }}
        </div>
    </div>

    <!-- Official Admin Response Card -->
    @if($ticket->admin_reply)
        <div class="card" style="border-left: 4px solid #10b981; background: rgba(16, 185, 129, 0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                <div class="user-profile-avatar" style="background: var(--gold-gradient); color: #000;"><i class="fa-solid fa-user-shield"></i></div>
                <div>
                    <strong style="color: #34d399;"><i class="fa-solid fa-circle-check"></i> Official Support Response</strong>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Replied at: {{ $ticket->replied_at ? $ticket->replied_at->format('M d, Y h:i A') : 'Recently' }}</div>
                </div>
            </div>

            <div style="background: rgba(8,10,15,0.7); padding: 1.2rem; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.2); color: #fff; font-size: 0.95rem; line-height: 1.6; whitespace: pre-wrap;">
                {{ $ticket->admin_reply }}
            </div>
        </div>
    @else
        <div class="card" style="text-align: center; padding: 2rem; color: var(--text-muted);">
            <i class="fa-solid fa-clock-rotate-left" style="font-size: 2.2rem; color: var(--gold-light); margin-bottom: 0.8rem; display: block;"></i>
            <h4 style="color: #fff; margin-bottom: 0.3rem;">Ticket Pending Response</h4>
            <p style="font-size: 0.88rem;">Our support team is reviewing your ticket. You will receive an official response right here shortly.</p>
        </div>
    @endif
</div>
@endsection
