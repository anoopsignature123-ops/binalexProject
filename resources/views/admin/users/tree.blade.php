@extends('admin.layouts.app')

@section('title', 'Genealogy Tree View')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-sitemap"></i> Genealogy & Referral Tree View</h2>
        <p style="color: var(--text-muted);">Interactive visual column hierarchy of team members and referral legs.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.index') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-arrow-left"></i> Users List</a>
        @if($rootUser->sponsor_id)
            <a href="{{ route('admin.users.tree', $rootUser->sponsor_id) }}" class="btn-gold"><i class="fa-solid fa-arrow-up"></i> Parent Sponsor Tree</a>
        @endif
    </div>
</div>

<div class="card" style="padding: 0; overflow: visible;">
    <x-genealogy-tree :rootUser="$rootUser" :isAdmin="true" />
</div>
@endsection
