@extends('admin.layouts.app')

@section('title', 'Manage Support Tickets')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-headset"></i> Support Tickets Management</h2>
        <p style="color: var(--text-muted);">Manage member support requests, respond to inquiries, and update ticket statuses.</p>
    </div>
</div>

<!-- Status Filter Tabs & Search Bar -->
<div class="card" style="padding: 1rem 1.2rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.support.index', ['status' => 'all', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'all' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">All Tickets</a>
            <a href="{{ route('admin.support.index', ['status' => 'pending', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'pending' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Pending</a>
            <a href="{{ route('admin.support.index', ['status' => 'replied', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'replied' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Replied</a>
            <a href="{{ route('admin.support.index', ['status' => 'resolved', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'resolved' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Resolved</a>
        </div>

        <form action="{{ route('admin.support.index') }}" method="GET" style="display: flex; gap: 8px;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" class="form-control" placeholder="Search ticket #, subject, or member..." value="{{ $search }}" style="width: 250px; padding: 6px 12px; font-size: 0.85rem;">
            <button type="submit" class="btn-gold" style="padding: 6px 12px;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
</div>

<!-- Support Tickets Table -->
<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>TICKET #</th>
                    <th>MEMBER DETAILS</th>
                    <th>SUBJECT & INQUIRY</th>
                    <th>CATEGORY</th>
                    <th>PRIORITY</th>
                    <th>STATUS</th>
                    <th>SUBMITTED AT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                    <tr>
                        <td>
                            <strong style="color: var(--gold-light);">{{ $t->ticket_number }}</strong>
                            <i class="fa-solid fa-copy copy-icon-btn" title="Copy Ticket #" onclick="copyToClipboard('{{ $t->ticket_number }}', 'Ticket #')"></i>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="user-profile-avatar">{{ strtoupper(substr($t->user->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <strong style="color: #fff;">{{ $t->user->name ?? 'Unknown' }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $t->user->referral_code ?? '' }} | {{ $t->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #fff;">{{ $t->subject }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $t->message }}</div>
                        </td>
                        <td>
                            <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); background: rgba(255,255,255,0.08); padding: 3px 8px; border-radius: 6px;">{{ $t->category }}</span>
                        </td>
                        <td>
                            @if($t->priority === 'urgent')
                                <span class="badge-status badge-rejected" style="font-size: 0.7rem;"><i class="fa-solid fa-fire"></i> URGENT</span>
                            @elseif($t->priority === 'high')
                                <span class="badge-status badge-pending" style="font-size: 0.7rem; color: #f97316;"><i class="fa-solid fa-triangle-exclamation"></i> HIGH</span>
                            @else
                                <span class="badge-status" style="font-size: 0.7rem; background: rgba(255,255,255,0.1); color: var(--text-muted);">{{ strtoupper($t->priority) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($t->status === 'replied')
                                <span class="badge-status badge-active"><i class="fa-solid fa-reply"></i> REPLIED</span>
                            @elseif($t->status === 'resolved')
                                <span class="badge-status badge-active" style="background: rgba(56,189,248,0.2); color: #38bdf8;"><i class="fa-solid fa-circle-check"></i> RESOLVED</span>
                            @elseif($t->status === 'pending')
                                <span class="badge-status badge-pending"><i class="fa-solid fa-clock"></i> PENDING</span>
                            @else
                                <span class="badge-status badge-rejected">{{ strtoupper($t->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $t->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $t->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <a href="{{ route('admin.support.show', $t->id) }}" class="btn-gold" style="padding: 4px 10px; font-size: 0.75rem; background: rgba(245,184,46,0.15); color: var(--gold-light);" title="View & Reply Ticket">
                                <i class="fa-solid fa-reply"></i> Reply
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">No support tickets found matching your query.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tickets->links('components.pagination') }}
</div>
@endsection
