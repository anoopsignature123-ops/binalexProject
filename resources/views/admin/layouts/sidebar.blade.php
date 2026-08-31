<div id="sidebarOverlay"
    class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"
    aria-hidden="true"></div>

<aside class="sidebar" id="sidebar">
    <div class="logo-section flex items-center gap-3 p-5 border-b border-border shrink-0">
        <div
            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shrink-0">
            <span class="text-white font-bold text-lg">B</span>
        </div>
        <div class="logo-text flex-1 min-w-0">
            <p class="text-lg font-bold text-text leading-tight">Binalex</p>
            <p class="text-xs text-muted truncate">Token admin Panel</p>
        </div>
        <button
            class="lg:hidden! flex w-8 h-8 items-center justify-center rounded-lg text-muted hover:bg-border/60 hover:text-text transition js-mobile-menu-toggle"
            aria-label="Close sidebar">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="py-4 flex-1">
        <div class="nav-section-title px-5 pt-4 pb-2 mt-2 text-[11px] font-bold uppercase tracking-[1.5px] text-muted">
            Main</div>
        <a class="nav-item active flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition"
            data-tooltip="Dashboard" href="{{ route('admin.dashboard') }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-section-title px-5 pt-4 pb-2 mt-2 text-[11px] font-bold uppercase tracking-[1.5px] text-muted">
            Management</div>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition"
            data-tooltip="User Management" href="{{ route('admin.users.index') }}">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span class="nav-text">User Management</span>
        </a>
        <div class="nav-section-title px-5 pt-4 pb-2 mt-2 text-[11px] font-bold uppercase tracking-[1.5px] text-muted">
            Ecosystem Config</div>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition"
            data-tooltip="Token Pricing" href="{{ route('admin.token-pricing.index') }}">
            <i data-lucide="coins" class="w-5 h-5 shrink-0"></i>
            <span class="nav-text">Token Pricing & Pool</span>
        </a>

    </nav>

    <div class="user-section mt-auto p-4 border-t border-border shrink-0">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-gradient-to-br from-emerald-500 to-teal-500">
                <span class="text-white font-semibold text-sm">AD</span>
            </div>
            <div class="user-info flex-1 min-w-0">
                <p class="font-semibold text-sm text-text truncate">admin</p>
                <p class="text-xs text-muted truncate">BINALEX</p>
            </div>
        </div>
    </div>
</aside>