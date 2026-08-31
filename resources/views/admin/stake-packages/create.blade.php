@extends('admin.layouts.master')

@section('title', 'Create Stake Package')

@section('content')
<div class="max-w-4xl"><div class="mb-6"><p class="text-sm font-semibold text-emerald-500 uppercase tracking-wider">Income Settings</p><h1 class="text-2xl lg:text-3xl font-bold text-text mt-2">Create stake package</h1><p class="text-muted mt-1">Add a package to the BINALEX ecosystem.</p></div><div class="rounded-2xl bg-panel border border-border p-5 sm:p-8"><form method="post" action="{{ route('admin.stake-packages.store') }}">@csrf @include('admin.stake-packages._form')</form></div></div>
@endsection
