@extends('user.layouts.app')

@section('title', 'P2P Coin Transfer')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-right-left"></i> P2P Wallet Transfer</h2>
        <p style="color: var(--text-muted);">Transfer ROI Wallet Coins or Earning Wallet Coins directly to another team member.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- ROI Wallet Card -->
    <div class="card" style="margin-bottom: 0; background: linear-gradient(145deg, rgba(245, 184, 46, 0.12) 0%, rgba(18, 24, 38, 0.85) 100%);">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">ROI Yield Wallet</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--gold-light); margin: 0.4rem 0;">
            {{ number_format($user->coin_balance, 2) }} <span style="font-size: 1rem;">BINALEX</span>
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-shield-halved"></i> Non-withdrawable directly; P2P Transfer & Staking enabled.</p>
    </div>

    <!-- Earning Wallet Card -->
    <div class="card" style="margin-bottom: 0; background: linear-gradient(145deg, rgba(16, 185, 129, 0.12) 0%, rgba(18, 24, 38, 0.85) 100%);">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Earning Commission Wallet</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #34d399; margin: 0.4rem 0;">
            {{ number_format($user->working_coin_balance, 2) }} <span style="font-size: 1rem;">BINALEX</span>
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-money-bill-transfer"></i> Fully withdrawable & P2P transferable.</p>
    </div>
</div>

<!-- P2P Transfer Form Card -->
<div class="card" style="max-width: 580px;">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem; font-size: 1.2rem;"><i class="fa-solid fa-paper-plane"></i> Send Coins to Member</h3>

    <form action="{{ route('user.p2p.store') }}" method="POST" data-confirm="Are you sure you want to transfer these coins to the recipient?">
        @csrf
        <div style="margin-bottom: 1.2rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Select Transfer Source Wallet</label>
            <select name="wallet_type" id="p2pWalletType" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; outline: none;">
                <option value="roi">ROI Yield Wallet (Available: {{ number_format($user->coin_balance, 2) }} Coins)</option>
                <option value="earning">Earning Commission Wallet (Available: {{ number_format($user->working_coin_balance, 2) }} Coins)</option>
            </select>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Recipient Member Referral Code / Email</label>
            <div style="position: relative;">
                <input type="text" id="recipientCodeInput" name="recipient_code" required placeholder="e.g. BT123456" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; outline: none;" onkeyup="verifyRecipient()">
            </div>
            <div id="recipientVerifyStatus" style="font-size: 0.82rem; margin-top: 5px;"></div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Transfer Amount (Coins)</label>
            <input type="number" step="0.01" min="1" name="coins_amount" required placeholder="Enter coins amount e.g. 50" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; outline: none;">
        </div>

        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.85rem;"><i class="fa-solid fa-paper-plane"></i> Confirm P2P Coin Transfer</button>
    </form>
</div>

<!-- P2P Transfer History Logs -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem; font-size: 1.1rem;"><i class="fa-solid fa-clock-rotate-left"></i> P2P Transfer Logs</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tx ID</th>
                    <th>Type</th>
                    <th>Source Wallet</th>
                    <th>Counterparty Member</th>
                    <th>Amount Coins</th>
                    <th>Transaction Remark / Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $tx)
                    @php $isSender = $tx->sender_id === auth()->id(); @endphp
                    <tr>
                        <td><strong style="color: var(--gold-light);">#P2P-{{ $tx->id }}</strong></td>
                        <td>
                            @if($isSender)
                                <span class="badge-status badge-rejected" style="background: rgba(239, 68, 68, 0.2); color: #f87171;">SENT OUT</span>
                            @else
                                <span class="badge-status badge-active" style="background: rgba(16, 185, 129, 0.2); color: #34d399;">RECEIVED</span>
                            @endif
                        </td>
                        <td>{{ strtoupper($tx->wallet_type) }} Wallet</td>
                        <td>
                            @if($isSender)
                                To: <strong>{{ $tx->receiver->name ?? 'Member' }}</strong> ({{ $tx->receiver->referral_code ?? '' }})
                            @else
                                From: <strong>{{ $tx->sender->name ?? 'Member' }}</strong> ({{ $tx->sender->referral_code ?? '' }})
                            @endif
                        </td>
                        <td style="font-weight: 800; color: {{ $isSender ? '#f87171' : '#34d399' }};">
                            {{ $isSender ? '-' : '+' }}{{ number_format($tx->coins_amount, 2) }} Coins
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; color: #f8fafc;">
                                <i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i>
                                @if($isSender)
                                    P2P Transfer sent to {{ $tx->receiver->name ?? 'Member' }} ({{ $tx->receiver->referral_code ?? '' }}) from {{ strtoupper($tx->wallet_type) }} Wallet
                                @else
                                    P2P Transfer received from {{ $tx->sender->name ?? 'Member' }} ({{ $tx->sender->referral_code ?? '' }}) into {{ strtoupper($tx->wallet_type) }} Wallet
                                @endif
                            </span>
                        </td>
                        <td>{{ $tx->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">No P2P coin transfers recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $transfers->links() }}
</div>

<script>
    let verifyTimer = null;
    function verifyRecipient() {
        clearTimeout(verifyTimer);
        const code = document.getElementById('recipientCodeInput').value.trim();
        const statusDiv = document.getElementById('recipientVerifyStatus');

        if (!code) {
            statusDiv.innerHTML = '';
            return;
        }

        statusDiv.innerHTML = '<span style="color: var(--gold-light);"><i class="fa-solid fa-spinner fa-spin"></i> Verifying member ID...</span>';

        verifyTimer = setTimeout(() => {
            fetch(`{{ route('user.p2p.verify') }}?code=${encodeURIComponent(code)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        statusDiv.innerHTML = `<span style="color: #34d399; font-weight: 700;"><i class="fa-solid fa-circle-check"></i> Verified Member: ${data.name} (${data.code})</span>`;
                    } else {
                        statusDiv.innerHTML = `<span style="color: #f87171; font-weight: 600;"><i class="fa-solid fa-circle-xmark"></i> ${data.message}</span>`;
                    }
                })
                .catch(err => {
                    statusDiv.innerHTML = '<span style="color: #f87171;">Error verifying recipient.</span>';
                });
        }, 350);
    }
</script>
@endsection
