@extends('admin.layouts.app')

@section('title', 'Add New User')

@section('content')
<style>
    .password-wrapper { position: relative; }
    .password-toggle-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--gold-light);
        z-index: 10;
    }
    .sponsor-info-box {
        margin-top: 0.5rem;
        padding: 0.6rem 0.9rem;
        border-radius: 8px;
        font-size: 0.82rem;
        display: none;
        align-items: center;
        gap: 8px;
        animation: fadeIn 0.3s ease forwards;
    }
    .sponsor-info-box.valid { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; display: flex; }
    .sponsor-info-box.invalid { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; display: flex; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-user-plus"></i> Add New User</h2>
        <p style="color: var(--text-muted);">Create a new member account with a mandatory Sponsor Referral ID.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-arrow-left"></i> Back to Users</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Sponsor Referral Code / ID <span style="color: #ef4444;">* (Mandatory)</span></label>
            <input type="text" id="adminSponsorInput" name="sponsor_code" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--gold-light); font-weight: 700;" placeholder="e.g. BT1000001" value="{{ old('sponsor_code') }}" required>
            <div id="adminSponsorBox" class="sponsor-info-box"></div>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Full Name <span style="color: #ef4444;">*</span></label>
            <input type="text" name="name" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff;" placeholder="John Doe" value="{{ old('name') }}" required>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Email Address <span style="color: #ef4444;">*</span></label>
            <input type="email" name="email" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff;" placeholder="user@example.com" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Mobile Number <span style="color: #ef4444;">*</span></label>
            <input type="text" name="mobile" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff;" placeholder="+1234567890" value="{{ old('mobile') }}" required>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Password <span style="color: #ef4444;">*</span></label>
            <div class="password-wrapper">
                <input type="password" id="adminPassword" name="password" style="width: 100%; padding: 0.8rem 2.5rem 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff;" placeholder="••••••••" required>
                <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('adminPassword', this)"></i>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted);">Confirm Password <span style="color: #ef4444;">*</span></label>
            <div class="password-wrapper">
                <input type="password" id="adminConfirmPassword" name="password_confirmation" style="width: 100%; padding: 0.8rem 2.5rem 0.8rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff;" placeholder="••••••••" required>
                <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('adminConfirmPassword', this)"></i>
            </div>
        </div>

        <div style="background: rgba(245, 184, 46, 0.08); border: 1px solid var(--border-gold); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-muted);">
            <i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> <strong>Status Note:</strong> Newly created user accounts will have an <strong>INACTIVE</strong> status by default. The account will automatically activate when the user buys/invests in a package.
        </div>

        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-user-plus"></i> CREATE USER ACCOUNT</button>
    </form>
</div>

<script>
    function togglePasswordVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // --- Real-time Sponsor Verification AJAX for Admin ---
    let adminSponsorTimeout = null;
    const adminSponsorInput = document.getElementById('adminSponsorInput');
    const adminSponsorBox = document.getElementById('adminSponsorBox');

    async function verifyAdminSponsorCode() {
        const code = adminSponsorInput.value.trim();
        if (!code) {
            adminSponsorBox.className = 'sponsor-info-box';
            adminSponsorBox.style.display = 'none';
            return;
        }

        try {
            const res = await fetch(`/sponsor-lookup/${encodeURIComponent(code)}`);
            const data = await res.json();

            if (data.success) {
                adminSponsorBox.className = 'sponsor-info-box valid';
                adminSponsorBox.innerHTML = `<i class="fa-solid fa-circle-check"></i> <div><strong>Sponsor Verified:</strong> ${data.name} (${data.email})</div>`;
            } else {
                adminSponsorBox.className = 'sponsor-info-box invalid';
                adminSponsorBox.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> <div>${data.message || 'Invalid Sponsor Referral ID'}</div>`;
            }
        } catch (err) {
            adminSponsorBox.className = 'sponsor-info-box invalid';
            adminSponsorBox.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> <div>Sponsor lookup failed</div>`;
        }
    }

    adminSponsorInput.addEventListener('input', () => {
        clearTimeout(adminSponsorTimeout);
        adminSponsorTimeout = setTimeout(verifyAdminSponsorCode, 300);
    });

    if (adminSponsorInput.value.trim()) {
        verifyAdminSponsorCode();
    }
</script>
@endsection
