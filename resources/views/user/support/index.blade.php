@extends('user.layouts.app')

@section('title', 'Help & Support Tickets')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-headset"></i> Help & Support Ticketing</h2>
        <p style="color: var(--text-muted);">Submit support requests to our technical team and track resolution progress in real time.</p>
    </div>
    <button class="btn-gold" onclick="toggleNewTicketForm()"><i class="fa-solid fa-plus"></i> Open New Support Ticket</button>
</div>

<!-- Compact, Centered Create Support Ticket Form -->
<div class="card" id="newTicketCard" style="display: none; max-width: 720px; margin: 0 auto 2rem auto; border: 1px solid var(--gold-primary); box-shadow: 0 15px 40px rgba(0,0,0,0.6), var(--gold-glow); animation: popIn 0.35s ease;">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(245, 184, 46, 0.2); padding-bottom: 1rem; margin-bottom: 1.5rem;">
        <h3 style="color: var(--gold-light); font-size: 1.25rem;"><i class="fa-solid fa-paper-plane"></i> Submit New Support Inquiry</h3>
        <button type="button" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;" onclick="toggleNewTicketForm()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    
    <form action="{{ route('user.support.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">SUBJECT / TITLE *</label>
            <input type="text" name="subject" class="form-control" placeholder="Brief summary of your query (e.g. Deposit Confirmation)" value="{{ old('subject') }}" required>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.2rem; margin-bottom: 1.2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">CATEGORY *</label>
                <select name="category" class="form-control" required>
                    <option value="deposit">Deposit Wallet Query</option>
                    <option value="withdrawal">Coin Withdrawal Payout</option>
                    <option value="staking">Staking Package Active</option>
                    <option value="account">Account & Security</option>
                    <option value="other" selected>General Inquiry / Other</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">PRIORITY LEVEL *</label>
                <select name="priority" class="form-control" required>
                    <option value="low">Low Priority</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="high">High Priority</option>
                    <option value="urgent">Urgent / Critical</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">DETAILED MESSAGE / DESCRIPTION *</label>
            <textarea name="message" rows="4" class="form-control" style="min-height: 110px; resize: vertical;" placeholder="Describe your issue or query in detail..." required>{{ old('message') }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button type="button" class="btn-gold" style="background: rgba(255,255,255,0.08); color: var(--text-muted);" onclick="toggleNewTicketForm()">Cancel</button>
            <button type="submit" class="btn-gold" style="padding: 0.75rem 1.8rem;"><i class="fa-solid fa-paper-plane"></i> SUBMIT TICKET</button>
        </div>
    </form>
</div>

<!-- Status Filter Tabs & Search Bar -->
<div class="card" style="padding: 1rem 1.2rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('user.support.index', ['status' => 'all', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'all' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">All Tickets</a>
            <a href="{{ route('user.support.index', ['status' => 'pending', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'pending' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Pending</a>
            <a href="{{ route('user.support.index', ['status' => 'replied', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'replied' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Replied</a>
            <a href="{{ route('user.support.index', ['status' => 'resolved', 'search' => $search]) }}" class="btn-gold" style="padding: 6px 14px; font-size: 0.82rem; {{ $status === 'resolved' ? '' : 'background: rgba(255,255,255,0.08); color: var(--text-muted);' }}">Resolved</a>
        </div>

        <form action="{{ route('user.support.index') }}" method="GET" style="display: flex; gap: 8px;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" class="form-control" placeholder="Search ticket # or subject..." value="{{ $search }}" style="width: 220px; padding: 6px 12px; font-size: 0.85rem;">
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
                    <th>SUBJECT & INQUIRY</th>
                    <th>CATEGORY</th>
                    <th>PRIORITY</th>
                    <th>STATUS</th>
                    <th>DATE SUBMITTED</th>
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
                            <div style="font-weight: 700; color: #fff;">{{ $t->subject }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $t->message }}</div>
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
                            <a href="{{ route('user.support.show', $t->id) }}" class="btn-gold" style="padding: 4px 10px; font-size: 0.75rem; background: rgba(56,189,248,0.2); color: #38bdf8; border-color: rgba(56,189,248,0.4);" title="View Ticket & Response">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">No support tickets found. Click <strong>Open New Support Ticket</strong> to contact support.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tickets->links('components.pagination') }}
</div>

<script>
    function toggleNewTicketForm() {
        const formCard = document.getElementById('newTicketCard');
        if (formCard.style.display === 'none') {
            formCard.style.display = 'block';
            formCard.scrollIntoView({ behavior: 'smooth' });
        } else {
            formCard.style.display = 'none';
        }
    }
</script>
@endsection
