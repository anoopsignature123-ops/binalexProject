<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - BINALEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <style>
        :root {
            --bg-dark: #07090e;
            --card-bg: rgba(14, 18, 28, 0.82);
            --gold-primary: #f5b82e;
            --gold-light: #ffd700;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #f5b82e 50%, #c88909 100%);
            --gold-glow: 0 0 30px rgba(245, 184, 46, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-gold: rgba(245, 184, 46, 0.3);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }

        body {
            background: linear-gradient(135deg, rgba(7, 9, 14, 0.35) 0%, rgba(12, 16, 26, 0.45) 100%), url('{{ asset("images/auth_bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* --- Global Glassmorphic Gold Page Loader --- */
        .global-loader-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(7, 9, 14, 0.94);
            backdrop-filter: blur(14px);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }
        .global-loader-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .global-loader-box { position: relative; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; }
        .global-loader-spinner {
            position: absolute; width: 100%; height: 100%;
            border: 3px solid rgba(245, 184, 46, 0.15);
            border-top: 3px solid var(--gold-light);
            border-right: 3px solid var(--gold-primary);
            border-radius: 50%; animation: spinLoader 0.85s linear infinite; box-shadow: 0 0 20px rgba(245, 184, 46, 0.3);
        }
        .global-loader-logo {
            width: 44px; height: 44px; background: var(--gold-gradient); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: #000; font-weight: 800; font-size: 1.3rem;
            animation: pulseLogo 1.4s ease-in-out infinite alternate;
        }
        .global-loader-text { margin-top: 1.2rem; font-size: 0.9rem; font-weight: 700; color: var(--gold-light); letter-spacing: 1px; }

        @keyframes spinLoader { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes pulseLogo { 0% { transform: scale(0.92); box-shadow: 0 0 10px rgba(245, 184, 46, 0.3); } 100% { transform: scale(1.08); box-shadow: 0 0 25px rgba(245, 184, 46, 0.7); } }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            backdrop-filter: blur(25px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.7), var(--gold-glow);
            animation: popIn 0.35s ease forwards;
        }

        @keyframes popIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }

        .auth-header { text-align: center; margin-bottom: 1.5rem; }

        .logo-badge {
            width: 50px;
            height: 50px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            box-shadow: var(--gold-glow);
        }

        .auth-title { font-size: 1.7rem; font-weight: 800; }
        .auth-title span { background: var(--gold-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-size: 0.88rem; color: var(--gold-light); font-weight: 700; }
        .form-control {
            width: 100%;
            padding: 0.8rem 1.1rem;
            background: rgba(8, 10, 15, 0.75);
            border: 1px solid var(--border-gold);
            border-radius: 12px;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
        }
        .form-control:focus { border-color: var(--gold-primary); box-shadow: var(--gold-glow); background: rgba(8, 10, 15, 0.9); }
        .form-control[readonly] { background: rgba(245, 184, 46, 0.1); cursor: not-allowed; border-color: var(--gold-primary); }

        .form-control.is-invalid { border-color: #ef4444 !important; background: rgba(239, 68, 68, 0.08) !important; box-shadow: 0 0 12px rgba(239, 68, 68, 0.3) !important; }
        .form-control.is-valid { border-color: #10b981 !important; box-shadow: 0 0 12px rgba(16, 185, 129, 0.25) !important; }

        .invalid-feedback-msg {
            color: #f87171;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-wrapper { position: relative; }
        .password-toggle-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gold-light);
            z-index: 10;
            font-size: 0.95rem;
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

        .btn-gold {
            width: 100%;
            background: var(--gold-gradient);
            color: #000;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            box-shadow: var(--gold-glow);
            transition: all 0.3s ease;
            margin-top: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-gold:hover { transform: translateY(-2px); opacity: 0.95; box-shadow: 0 0 35px rgba(245, 184, 46, 0.6); }

        .auth-footer { text-align: center; margin-top: 1.4rem; color: var(--text-muted); font-size: 0.88rem; }
        .auth-footer a { color: var(--gold-light); text-decoration: none; font-weight: 700; }
        .alert-error { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; padding: 0.8rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.85rem; }
    </style>
</head>
<body>
    <!-- Global Glassmorphic Gold Page Loader -->
    <div id="globalPageLoader" class="global-loader-overlay">
        <div class="global-loader-box">
            <div class="global-loader-spinner"></div>
            <div class="global-loader-logo">B</div>
        </div>
        <div class="global-loader-text">Loading BINALEX...</div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" style="height: 85px; width: auto; object-fit: contain; margin-bottom: 0.8rem; filter: drop-shadow(0 0 15px rgba(245, 184, 46, 0.45));">
            <h2 class="auth-title">Create <span>Account</span></h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">Join BINALEX</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $err)
                    <div><i class="fa-solid fa-circle-exclamation"></i> {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('user.register.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>
                    <i class="fa-solid fa-user-plus"></i> Sponsor Referral ID <span style="color: #ef4444;">*</span>
                    @if(!empty($refCode))
                        <span style="float: right; color: var(--gold-light); font-weight: 700; font-size: 0.75rem;"><i class="fa-solid fa-link"></i> Auto-filled from Link</span>
                    @endif
                </label>
                <input type="text" id="sponsorInput" name="sponsor_code" class="form-control" style="color: var(--gold-light); font-weight: 700;" placeholder="e.g. BT1000001" value="{{ old('sponsor_code', $refCode) }}" {{ !empty($refCode) ? 'readonly' : '' }} required>
                <div id="sponsorInfoBox" class="sponsor-info-box"></div>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Full Name *</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Email Address *</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-phone"></i> Mobile Number *</label>
                <input type="text" name="mobile" class="form-control" placeholder="+1234567890" value="{{ old('mobile') }}" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Security Password *</label>
                <div class="password-wrapper">
                    <input type="password" id="userRegPassword" name="password" class="form-control" placeholder="••••••••" style="padding-right: 2.5rem;" required>
                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('userRegPassword', this)"></i>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-check-double"></i> Confirm Password *</label>
                <div class="password-wrapper">
                    <input type="password" id="userRegConfirmPassword" name="password_confirmation" class="form-control" placeholder="••••••••" style="padding-right: 2.5rem;" required>
                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePasswordVisibility('userRegConfirmPassword', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-gold"><i class="fa-solid fa-user-check"></i> REGISTER NOW</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('user.login') }}">Login Here &rarr;</a>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('globalPageLoader');
            if (loader) loader.classList.add('hidden');
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form').forEach(form => {
                const inputs = form.querySelectorAll('input[required], input[type="email"]');
                inputs.forEach(input => {
                    ['input', 'keyup', 'blur'].forEach(evt => {
                        input.addEventListener(evt, () => validateField(input));
                    });
                });

                form.addEventListener('submit', (e) => {
                    let isValid = true;
                    inputs.forEach(input => {
                        if (!validateField(input)) isValid = false;
                    });

                    if (!isValid) {
                        e.preventDefault();
                        return;
                    }

                    const loader = document.getElementById('globalPageLoader');
                    if (loader) loader.classList.remove('hidden');
                });
            });

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    const href = link.getAttribute('href');
                    if (href && !href.startsWith('#') && !href.startsWith('javascript:') && link.target !== '_blank') {
                        const loader = document.getElementById('globalPageLoader');
                        if (loader) loader.classList.remove('hidden');
                    }
                });
            });
        });

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

        function validateField(input) {
            let isValid = true;
            let msg = '';
            const value = input.value.trim();
            const labelEl = input.parentNode.querySelector('label') || input.closest('.form-group')?.querySelector('label');
            const fieldName = labelEl ? labelEl.innerText.replace('*', '').trim() : 'Field';

            if (input.hasAttribute('required') && !value) {
                isValid = false;
                msg = `${fieldName} is required.`;
            } else if (input.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    msg = 'Please enter a valid email address.';
                }
            } else if (input.name === 'mobile' && value) {
                if (value.length < 7) {
                    isValid = false;
                    msg = 'Please enter a valid mobile number.';
                }
            } else if (input.name === 'password' && value) {
                if (value.length < 6) {
                    isValid = false;
                    msg = 'Password must be at least 6 characters.';
                }
            } else if (input.name === 'password_confirmation' && value) {
                const pwd = input.form.querySelector('input[name="password"]');
                if (pwd && value !== pwd.value) {
                    isValid = false;
                    msg = 'Passwords do not match.';
                }
            }

            const wrapper = input.closest('.password-wrapper') || input.parentNode;
            const targetContainer = input.closest('.password-wrapper') ? input.closest('.password-wrapper').parentNode : wrapper;
            
            let existingMsg = targetContainer.querySelector('.invalid-feedback-msg');
            if (existingMsg) existingMsg.remove();

            if (!isValid) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback-msg';
                errorDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${msg}`;
                targetContainer.appendChild(errorDiv);
            } else if (value) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-invalid', 'is-valid');
            }

            return isValid;
        }

        // --- Real-time Sponsor Verification AJAX ---
        let sponsorTimeout = null;
        const sponsorInput = document.getElementById('sponsorInput');
        const sponsorBox = document.getElementById('sponsorInfoBox');

        async function verifySponsorCode() {
            const code = sponsorInput.value.trim();
            if (!code) {
                sponsorBox.className = 'sponsor-info-box';
                sponsorBox.style.display = 'none';
                return;
            }

            try {
                const res = await fetch(`/sponsor-lookup/${encodeURIComponent(code)}`);
                const data = await res.json();

                if (data.success) {
                    sponsorBox.className = 'sponsor-info-box valid';
                    sponsorBox.innerHTML = `<i class="fa-solid fa-circle-check"></i> <div><strong>Sponsor Verified:</strong> ${data.name} (${data.email})</div>`;
                } else {
                    sponsorBox.className = 'sponsor-info-box invalid';
                    sponsorBox.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> <div>${data.message || 'Invalid Sponsor Referral ID'}</div>`;
                }
            } catch (err) {
                sponsorBox.className = 'sponsor-info-box invalid';
                sponsorBox.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> <div>Sponsor lookup failed</div>`;
            }
        }

        sponsorInput.addEventListener('input', () => {
            clearTimeout(sponsorTimeout);
            sponsorTimeout = setTimeout(verifySponsorCode, 300);
        });

        if (sponsorInput.value.trim()) {
            verifySponsorCode();
        }
    </script>
</body>
</html>
