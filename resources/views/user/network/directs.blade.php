@extends('user.layouts.app')

@section('title', 'My Direct Referrals')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-users"></i> My Direct Referrals</h2>
        <p style="color: var(--text-muted);">List of members registered directly using your referral link: <strong>{{ auth()->user()->referral_code }}</strong></p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('user.network.tree') }}" class="btn-gold"><i class="fa-solid fa-sitemap"></i> View Total Team Tree</a>
        <button class="btn-gold" style="background: rgba(245, 184, 46, 0.2); color: var(--gold-light);" onclick="copyToClipboard('{{ route('user.register', ['ref' => auth()->user()->referral_code]) }}', 'Shareable Referral Link')">
            <i class="fa-solid fa-share-nodes"></i> Copy Referral Link
        </button>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Referral Code</th>
                    <th>Email Address</th>
                    <th>Staked Volume</th>
                    <th>Sub-Directs</th>
                    <th>Status</th>
                    <th>Registration Date & Time</th>
                    <th>Activation Date & Time</th>
                    <th>Team Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($directs as $d)
                    @php $actDate = $d->first_activation_date; @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="header-user-avatar">
                                    {{ strtoupper(substr($d->name ?? $d->email, 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="color: var(--text-main); font-size: 0.95rem;">{{ $d->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--gold-light); font-size: 0.92rem;">{{ $d->referral_code }}</strong>
                            <i class="fa-solid fa-copy copy-icon-btn" title="Copy Referral Code" onclick="copyToClipboard('{{ $d->referral_code }}', 'Member Code')"></i>
                        </td>
                        <td style="color: var(--text-muted);">{{ $d->email }}</td>
                        <td style="color: var(--gold-light); font-weight: 700;">${{ number_format($d->total_staked_usd, 2) }}</td>
                        <td>{{ $d->directs_count }} Directs</td>
                        <td><span class="badge-status badge-{{ $d->status }}">{{ strtoupper($d->status) }}</span></td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $d->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $d->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($actDate)
                                <div style="font-size: 0.85rem; font-weight: 700; color: #34d399;">{{ $actDate->format('M d, Y') }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $actDate->format('h:i A') }}</div>
                            @else
                                <span class="badge-status badge-rejected" style="font-size: 0.7rem; padding: 2px 8px;">NOT ACTIVATED</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user.network.tree', $d->id) }}" class="btn-gold" style="padding: 4px 10px; font-size: 0.78rem;">
                                <i class="fa-solid fa-sitemap"></i> View Tree
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 2rem;">You have no direct referrals registered under your link yet. Share your referral link to build your team!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $directs->links() }}
</div>
@endsection
