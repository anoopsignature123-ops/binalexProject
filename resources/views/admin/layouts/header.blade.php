<header class="sticky top-0 z-50 glass @container">
    <div class="flex items-center justify-between px-2 sm:px-6 py-2 sm:py-4">
        <div class="flex min-w-0 items-center gap-2 sm:gap-4">
            <button
                class="lg:hidden flex size-11 items-center justify-center rounded-xl bg-panel border border-border text-text hover:bg-accent/10 transition js-mobile-menu-toggle"
                id="mobileMenuBtn" aria-label="Toggle Mobile Menu">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <button
                class="hidden lg:flex size-11 items-center justify-center rounded-xl bg-panel border border-border text-text hover:bg-accent/10 transition js-sidebar-toggle"
                aria-label="Toggle Sidebar">
                <i data-lucide="panel-left" class="w-5 h-5"></i>
            </button>
            <div class="shrink-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">BINALEX</p>
                <p class="text-sm font-medium text-text">Admin Panel</p>
            </div>
            <div class="relative hidden min-w-0 md:block">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                <input type="search" id="globalSearch" placeholder="Search users, referral IDs..."
                    class="w-52 lg:w-80 pl-10 pr-4 py-2.5 rounded-xl bg-bg border border-border text-sm text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition">
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <div
                class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[11px] font-semibold">
                <span class="live-dot !bg-emerald-500"></span>
                ONLINE
            </div>
            <button
                class="flex size-11 items-center justify-center rounded-xl bg-panel border border-border text-text hover:bg-accent/10 transition"
                id="themeToggle" aria-label="Toggle Theme"
                onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');">
                <i data-lucide="moon" class="w-5 h-5 dark:hidden"></i>
                <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
            </button>
            <div class="dropdown relative">
                <button class="flex items-center gap-2 p-1 rounded-xl hover:bg-border/50 transition js-dropdown-toggle" aria-label="Open admin profile menu">
                    <span
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">AD</span>
                    </span>
                    <span class="hidden sm:block text-sm font-semibold text-text">admin</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-muted hidden sm:block"></i>
                </button>
                <div class="dropdown-menu w-56 mt-2 rounded-2xl bg-panel border border-border shadow-2xl overflow-hidden p-2 right-0">
                    <div class="p-3 mb-1 border-b border-border">
                        <p class="font-semibold text-text">Admin Account</p>
                        <p class="text-xs text-muted">BINALEX</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text hover:bg-accent/10 transition">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i><span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text hover:bg-accent/10 transition">
                        <i data-lucide="users" class="w-4 h-4"></i><span>User Management</span>
                    </a>
                    <div class="mt-1 pt-1 border-t border-border">
                        <form action="{{ route('admin.logout') }}" method="post">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-500/10 transition">
                                <i data-lucide="log-out" class="w-4 h-4"></i><span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>