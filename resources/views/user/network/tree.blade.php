@extends('user.layouts.app')

@section('title', 'My Total Team (Tree View)')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-sitemap"></i> My Total Team (Genealogy Tree)</h2>
        <p style="color: var(--text-muted);">Explore your entire downline referral network tree and team legs.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('user.network.directs') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-users"></i> My Directs</a>
        @if($rootUser->id !== auth()->id() && auth()->user()->isAncestorOf($rootUser->sponsor_id ?? 0))
            <a href="{{ route('user.network.tree', $rootUser->sponsor_id) }}" class="btn-gold"><i class="fa-solid fa-arrow-up"></i> Parent Leg</a>
        @endif
        @if($rootUser->id !== auth()->id())
            <a href="{{ route('user.network.tree') }}" class="btn-gold"><i class="fa-solid fa-crown"></i> My Top Root Tree</a>
        @endif
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    <x-genealogy-tree :rootUser="$rootUser" :isAdmin="false" />
</div>
@endsection
