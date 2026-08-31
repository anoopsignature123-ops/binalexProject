@if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label for="name" class="block text-sm font-medium text-text mb-2">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user?->name) }}" required
            class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
            placeholder="Full name">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-text mb-2">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" required
            class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
            placeholder="user@example.com">
    </div>
    <div>
        <label for="mobile" class="block text-sm font-medium text-text mb-2">Mobile</label>
        <input id="mobile" name="mobile" type="text" value="{{ old('mobile', $user?->mobile) }}" required
            class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
            placeholder="Mobile number">
    </div>
    <div>
        <label for="sponsor_referral_code" class="block text-sm font-medium text-text mb-2">Sponsor Referral ID</label>
        <input id="sponsor_referral_code" name="sponsor_referral_code" type="text"
            value="{{ old('sponsor_referral_code', $user?->sponsor?->referral_code) }}" required
            class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
            placeholder="BT9876453">
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-text mb-2">Password
            {{ $user ? '(leave blank to keep current)' : '' }}</label>
        <div class="relative">
            <input id="password" name="password" type="password" {{ $user ? '' : 'required' }}
                class="w-full px-4 py-3 pr-12 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
                placeholder="Minimum 8 characters">
            <button type="button" data-password-toggle="password" aria-label="Show password"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text"><i data-lucide="eye"
                    class="w-4 h-4"></i></button>
        </div>
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-text mb-2">Confirm password</label>
        <div class="relative">
            <input id="password_confirmation" name="password_confirmation" type="password" {{ $user ? '' : 'required' }}
                class="w-full px-4 py-3 pr-12 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20"
                placeholder="Repeat password">
            <button type="button" data-password-toggle="password_confirmation" aria-label="Show password"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text"><i data-lucide="eye"
                    class="w-4 h-4"></i></button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
            const icon = button.querySelector('[data-lucide]');
            icon.setAttribute('data-lucide', visible ? 'eye' : 'eye-off');
            window.lucide?.createIcons();
        });
    });

</script>

<div class="flex items-center gap-3 mt-6">
    <button type="submit"
        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/20 hover:-translate-y-0.5 transition"><i
            data-lucide="save" class="w-4 h-4"></i>{{ $user ? 'Update user' : 'Create user' }}</button>
    <a href="{{ route('admin.users.index') }}"
        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-border text-sm font-semibold text-text hover:bg-border/30 transition">Cancel</a>
</div>