@extends('admin.layouts.app')

@section('title', 'Manage Ticket #' . $ticket->ticket_number)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.support.index') }}" class="btn-gold" style="padding: 4px 12px; font-size: 0.8rem; background: rgba(255,255,255,0.08); color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Back to Support Tickets</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--gold-light);">TICKET #{{ $ticket->ticket_number }}</div>
            <h2 style="color: #fff; margin-top: 0.2rem;">{{ $ticket->subject }}</h2>
            <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.3rem;">
                Member: <strong style="color: #fff;">{{ $ticket->user->name ?? 'User' }}</strong> ({{ $ticket->user->email ?? '' }} | {{ $ticket->user->referral_code ?? '' }}) | 
                Category: <span style="color: #fff; font-weight: 700; text-transform: uppercase;">{{ $ticket->category }}</span> | 
                Priority: <span style="color: var(--gold-light); font-weight: 700; text-transform: uppercase;">{{ $ticket->priority }}</span>
            </div>
        </div>

        <div>
            @if($ticket->status === 'replied')
                <span class="badge-status badge-active" style="font-size: 0.9rem; padding: 6px 14px;"><i class="fa-solid fa-reply"></i> REPLIED</span>
            @elseif($ticket->status === 'resolved')
                <span class="badge-status badge-active" style="font-size: 0.9rem; padding: 6px 14px; background: rgba(56,189,248,0.2); color: #38bdf8;"><i class="fa-solid fa-circle-check"></i> RESOLVED</span>
            @elseif($ticket->status === 'pending')
                <span class="badge-status badge-pending" style="font-size: 0.9rem; padding: 6px 14px;"><i class="fa-solid fa-clock"></i> PENDING RESPONSE</span>
            @else
                <span class="badge-status badge-rejected" style="font-size: 0.9rem; padding: 6px 14px;">{{ strtoupper($ticket->status) }}</span>
            @endif
        </div>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Original Member Message Card -->
    <div class="card" style="border-left: 4px solid var(--gold-primary);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="user-profile-avatar">{{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <strong style="color: #fff;">{{ $ticket->user->name ?? 'Member' }}</strong> (Member Inquiry)
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Submitted at: {{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>

            <a href="{{ route('admin.users.show', $ticket->user_id) }}" class="btn-gold" style="padding: 4px 10px; font-size: 0.75rem; background: rgba(56,189,248,0.2); color: #38bdf8; border-color: rgba(56,189,248,0.4);" title="View User Details">
                <i class="fa-solid fa-user"></i> View Profile
            </a>
        </div>

        <div style="background: rgba(8,10,15,0.6); padding: 1.2rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); color: var(--text-main); font-size: 0.95rem; line-height: 1.6; whitespace: pre-wrap;">{{ $ticket->message }}</div>
    </div>

    <!-- Response Form & Update Status Card -->
    <div class="card" style="border-top: 3px solid var(--gold-light);">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-reply-all"></i> Official Admin Response & Ticket Action</h3>

        <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">RESPONSE MESSAGE *</label>
                <textarea name="admin_reply" rows="5" class="form-control" placeholder="Write official response message to member..." required>{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.2rem; background: rgba(8,10,15,0.6); padding: 1.2rem; border-radius: 12px; border: 1px solid rgba(245, 184, 46, 0.2);">
                <div style="min-width: 260px; max-width: 320px; width: 100%;">
                    <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">UPDATE TICKET STATUS *</label>
                    <select name="status" class="form-control" style="height: 42px;" required>
                        <option value="replied" {{ $ticket->status === 'replied' ? 'selected' : '' }}>Replied (Awaiting Member)</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved (Issue Fixed)</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div style="display: flex; align-items: flex-end; justify-content: flex-end; flex-grow: 1;">
                    <button type="submit" class="btn-gold" style="padding: 0.75rem 2rem; font-size: 0.95rem; font-weight: 800;"><i class="fa-solid fa-paper-plane"></i> POST OFFICIAL RESPONSE</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
