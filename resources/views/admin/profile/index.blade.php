@extends('admin.layouts.app')

@section('title', 'Manage Admin Profile')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-user-shield"></i> Manage Admin Profile & Password</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Update your administrator account profile credentials and manage security passwords.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
    <!-- Card 1: Admin Profile Details -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-id-card"></i> Personal Information</h3>

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">ADMINISTRATOR NAME *</label>
                <input type="text" name="name" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" value="{{ old('name', $admin->name) }}" required>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">EMAIL ADDRESS *</label>
                <input type="email" name="email" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" value="{{ old('email', $admin->email) }}" required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">MOBILE NUMBER *</label>
                <input type="text" name="mobile" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" value="{{ old('mobile', $admin->mobile) }}" required>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-floppy-disk"></i> UPDATE PROFILE DETAILS</button>
        </form>
    </div>

    <!-- Card 2: Change Password -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-key"></i> Security & Change Password</h3>

        <form action="{{ route('admin.profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">CURRENT PASSWORD *</label>
                <input type="password" name="current_password" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" placeholder="Enter current admin password" required>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">NEW PASSWORD *</label>
                <input type="password" name="password" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" placeholder="Min 6 characters" required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">CONFIRM NEW PASSWORD *</label>
                <input type="password" name="password_confirmation" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" placeholder="Re-enter new password" required>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-lock"></i> CHANGE ADMIN PASSWORD</button>
        </form>
    </div>
</div>
@endsection
