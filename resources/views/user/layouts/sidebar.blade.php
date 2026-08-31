<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" aria-hidden="true"></div>
<aside class="sidebar" id="sidebar">
    <div class="logo-section flex items-center gap-3 p-5 border-b border-border shrink-0">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shrink-0"><span class="text-white font-bold text-lg">B</span></div>
        <div class="logo-text flex-1 min-w-0"><p class="text-lg font-bold text-text leading-tight">Binalex</p><p class="text-xs text-muted truncate">Token User Panel</p></div>
        <button class="lg:hidden! flex w-8 h-8 items-center justify-center rounded-lg text-muted hover:bg-border/60 hover:text-text transition js-mobile-menu-toggle" aria-label="Close sidebar"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <nav class="py-4 flex-1">
        <div class="nav-section-title px-5 pt-4 pb-2 mt-2 text-[11px] font-bold uppercase tracking-[1.5px] text-muted">Workspace</div>
        <a class="nav-item active flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="Dashboard" href="{{ route('user.dashboard') }}"><i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i><span class="nav-text">Dashboard</span></a>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="My Wallet" href="#"><i data-lucide="wallet" class="w-5 h-5 shrink-0"></i><span class="nav-text">My Wallet</span></a>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="Deposit" href="#"><i data-lucide="arrow-down-to-line" class="w-5 h-5 shrink-0"></i><span class="nav-text">Deposit</span></a>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="Withdraw" href="#"><i data-lucide="arrow-up-from-line" class="w-5 h-5 shrink-0"></i><span class="nav-text">Withdraw</span></a>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="Earnings" href="#"><i data-lucide="trending-up" class="w-5 h-5 shrink-0"></i><span class="nav-text">Earnings</span></a>
        <div class="nav-section-title px-5 pt-4 pb-2 mt-2 text-[11px] font-bold uppercase tracking-[1.5px] text-muted">Account</div>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="My Profile" href="#"><i data-lucide="user-round" class="w-5 h-5 shrink-0"></i><span class="nav-text">My Profile</span></a>
        <a class="nav-item flex items-center gap-3 mx-3 my-0.5 px-4 py-3 rounded-xl text-sm font-medium text-muted hover:bg-accent/10 hover:text-text transition" data-tooltip="Settings" href="#"><i data-lucide="settings" class="w-5 h-5 shrink-0"></i><span class="nav-text">Settings</span></a>
    </nav>
    <div class="user-section mt-auto p-4 border-t border-border shrink-0"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-gradient-to-br from-emerald-500 to-teal-500"><span class="text-white font-semibold text-sm">U</span></div><div class="user-info flex-1 min-w-0"><p class="font-semibold text-sm text-text truncate">User Account</p><p class="text-xs text-muted truncate">BINALEX</p></div></div></div>
</aside>
