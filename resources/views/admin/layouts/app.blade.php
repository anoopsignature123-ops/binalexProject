<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - BINALEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <style>
        :root {
            --bg-dark: #07090e;
            --sidebar-bg: rgba(14, 18, 28, 0.92);
            --card-bg: rgba(14, 18, 28, 0.82);
            --gold-primary: #f5b82e;
            --gold-light: #ffd700;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #f5b82e 50%, #c88909 100%);
            --gold-glow: 0 0 20px rgba(245, 184, 46, 0.3);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-gold: rgba(245, 184, 46, 0.28);
            --danger: #ef4444;
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, rgba(7, 9, 14, 0.85) 0%, rgba(12, 16, 26, 0.88) 100%), url('{{ asset("images/auth_bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* --- Global Glassmorphic Gold Page Loader --- */
        .global-loader-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(7, 9, 14, 0.93);
            backdrop-filter: blur(12px);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }
        .global-loader-overlay.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .global-loader-box {
            position: relative;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .global-loader-spinner {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid rgba(245, 184, 46, 0.15);
            border-top: 3px solid var(--gold-light);
            border-right: 3px solid var(--gold-primary);
            border-radius: 50%;
            animation: spinLoader 0.85s linear infinite;
            box-shadow: 0 0 20px rgba(245, 184, 46, 0.3);
        }
        .global-loader-logo {
            width: 44px;
            height: 44px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 800;
            font-size: 1.3rem;
            animation: pulseLogo 1.4s ease-in-out infinite alternate;
        }
        .global-loader-text {
            margin-top: 1.2rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--gold-light);
            letter-spacing: 1px;
        }

        @keyframes spinLoader {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulseLogo {
            0% { transform: scale(0.92); box-shadow: 0 0 10px rgba(245, 184, 46, 0.3); }
            100% { transform: scale(1.08); box-shadow: 0 0 25px rgba(245, 184, 46, 0.7); }
        }

        .sidebar {
            width: 270px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            border-right: 1.5px solid var(--border-gold);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        /* --- Sleek Metallic Gold Custom Scrollbar --- */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(8, 10, 15, 0.8);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(245, 184, 46, 0.3);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gold-light);
        }

        /* --- Desktop Sidebar Collapsed State --- */
        body.sidebar-collapsed .sidebar {
            transform: translateX(-270px);
        }
        body.sidebar-collapsed .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }

        .sidebar-brand {
            padding: 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            border-bottom: 1px solid var(--border-gold);
            position: sticky;
            top: 0;
            background: var(--sidebar-bg);
            z-index: 10;
        }

        .logo-badge {
            width: 38px;
            height: 38px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 800;
        }

        .close-sidebar-btn {
            display: none;
            background: transparent;
            border: none;
            color: var(--gold-light);
            font-size: 1.4rem;
            cursor: pointer;
        }

        .nav-menu-heading {
            padding: 1.2rem 1.2rem 0.4rem;
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--gold-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-menu {
            list-style: none;
            padding: 0.8rem 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 12px;
            border: 1px solid rgba(245, 184, 46, 0.12);
            background: rgba(8, 10, 15, 0.4);
            transition: all 0.25s ease;
        }

        .nav-item a:hover {
            background: rgba(245, 184, 46, 0.12);
            color: var(--gold-light);
            border-color: var(--gold-primary);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(245, 184, 46, 0.15);
        }

        .nav-item.active a {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(245, 184, 46, 0.3) 100%);
            color: var(--gold-light);
            border: 1px solid var(--gold-light);
            box-shadow: var(--gold-glow);
            font-weight: 800;
        }

        .main-content {
            margin-left: 270px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 270px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* --- Premium Header Bar Styling --- */
        .header {
            height: 75px;
            padding: 0 1.5rem;
            background: rgba(12, 16, 26, 0.92);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-toggle-btn {
            background: rgba(18, 24, 38, 0.8);
            border: 1px solid var(--border-gold);
            color: var(--gold-light);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .header-toggle-btn:hover {
            background: rgba(245, 184, 46, 0.15);
            box-shadow: var(--gold-glow);
        }

        .header-search-bar {
            position: relative;
            width: 320px;
        }
        .header-search-bar i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-light);
            font-size: 0.9rem;
        }
        .header-search-input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            background: rgba(8, 10, 15, 0.7);
            border: 1px solid var(--border-gold);
            border-radius: 30px;
            color: var(--text-main);
            font-size: 0.88rem;
            outline: none;
            transition: all 0.3s ease;
        }
        .header-search-input:focus {
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
            width: 360px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .header-action-btn {
            background: rgba(18, 24, 38, 0.8);
            border: 1px solid var(--border-gold);
            color: var(--gold-light);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
        }
        .header-action-btn:hover {
            background: rgba(245, 184, 46, 0.15);
            transform: translateY(-2px);
            box-shadow: var(--gold-glow);
        }
        .header-action-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            background: var(--gold-light);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--gold-light);
        }

        .header-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 4px 6px;
            background: rgba(18, 24, 38, 0.8);
            border: 1px solid var(--border-gold);
            border-radius: 30px;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        .header-user-card:hover {
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
        }
        .header-user-avatar {
            width: 36px;
            height: 36px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 800;
            font-size: 0.95rem;
        }
        .header-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .header-user-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-main);
        }
        .header-user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .header-dropdown-menu {
            display: none;
            position: absolute;
            top: 52px;
            right: 0;
            width: 200px;
            background: rgba(14, 18, 28, 0.98);
            border: 1px solid var(--border-gold);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
            backdrop-filter: blur(15px);
            padding: 0.5rem;
            z-index: 1000;
            animation: fadeIn 0.2s ease forwards;
        }
        .header-dropdown-menu.show { display: block; }
        .header-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.65rem 0.9rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.2s;
            background: transparent;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        .header-dropdown-item:hover {
            background: rgba(245, 184, 46, 0.12);
            color: var(--gold-light);
        }

        .content-body { padding: 1.5rem; flex-grow: 1; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 950;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #000;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .custom-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .custom-table th, .custom-table td { padding: 0.8rem 0.85rem; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.08); vertical-align: middle; white-space: nowrap; }
        .custom-table th { background: rgba(245, 184, 46, 0.1); color: var(--gold-light); font-size: 0.78rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; }

        /* --- User Profile Avatar & Copy Icon Styles --- */
        .user-profile-avatar {
            width: 36px;
            height: 36px;
            background: var(--gold-gradient);
            border: 1px solid var(--gold-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 0 10px rgba(245, 184, 46, 0.35);
        }

        .copy-icon-btn {
            color: var(--gold-light);
            cursor: pointer;
            font-size: 0.82rem;
            margin-left: 6px;
            transition: all 0.2s ease;
        }
        .copy-icon-btn:hover {
            color: #fff;
            transform: scale(1.25);
        }

        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
        .badge-active, .badge-approved { background: rgba(16, 185, 129, 0.2); color: #34d399; }
        .badge-pending { background: rgba(245, 184, 46, 0.2); color: #f5b82e; }
        .badge-rejected, .badge-inactive { background: rgba(239, 68, 68, 0.2); color: #f87171; }

        /* --- Sleek Metallic Gold Custom Pagination Styling --- */
        .custom-pagination-wrapper, .pagination-wrapper, .pagination, nav[role="navigation"] {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            flex-wrap: wrap !important;
            gap: 1rem !important;
            margin-top: 1.5rem !important;
            padding-top: 1rem !important;
            border-top: 1px solid rgba(245, 184, 46, 0.15) !important;
            width: 100% !important;
        }

        .pagination-info, nav[role="navigation"] p {
            font-size: 0.85rem !important;
            color: var(--text-muted) !important;
            margin: 0 !important;
            font-weight: 500 !important;
        }
        .pagination-info span, nav[role="navigation"] p span {
            color: var(--gold-light) !important;
            font-weight: 800 !important;
        }

        .pagination-links, ul.pagination {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .custom-pagination-wrapper .page-item,
        .pagination .page-item,
        nav[role="navigation"] .page-item {
            display: inline-block !important;
            margin: 0 !important;
        }

        .custom-pagination-wrapper .page-link,
        .pagination .page-link,
        nav[role="navigation"] a,
        nav[role="navigation"] span[aria-current="page"],
        nav[role="navigation"] span[aria-disabled="true"] {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 36px !important;
            height: 36px !important;
            padding: 0 12px !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            color: var(--text-main) !important;
            background: rgba(14, 18, 28, 0.85) !important;
            border: 1px solid var(--border-gold) !important;
            border-radius: 10px !important;
            text-decoration: none !important;
            transition: all 0.25s ease !important;
            cursor: pointer !important;
        }

        .custom-pagination-wrapper a.page-link:hover,
        .pagination a.page-link:hover,
        nav[role="navigation"] a:hover {
            background: rgba(245, 184, 46, 0.25) !important;
            border-color: var(--gold-primary) !important;
            color: var(--gold-light) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(245, 184, 46, 0.3) !important;
        }

        .custom-pagination-wrapper .page-item.active .page-link,
        .pagination .page-item.active .page-link,
        nav[role="navigation"] span[aria-current="page"] {
            background: var(--gold-gradient) !important;
            color: #000 !important;
            border-color: var(--gold-light) !important;
            box-shadow: var(--gold-glow) !important;
            font-weight: 800 !important;
        }

        .custom-pagination-wrapper .page-item.disabled .page-link,
        .pagination .page-item.disabled .page-link,
        nav[role="navigation"] span[aria-disabled="true"] {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        nav[role="navigation"] svg {
            width: 1.2rem !important;
            height: 1.2rem !important;
            fill: currentColor !important;
        }

        /* --- Universal Form Controls & Textarea Dark Gold Styling --- */
        .form-control, input.form-control, textarea.form-control, select.form-control {
            width: 100% !important;
            display: block !important;
            background: rgba(8, 10, 15, 0.8) !important;
            border: 1px solid var(--border-gold) !important;
            border-radius: 12px !important;
            color: #fff !important;
            font-size: 0.92rem !important;
            padding: 0.75rem 1rem !important;
            outline: none !important;
            transition: all 0.3s ease !important;
            box-sizing: border-box !important;
        }
        textarea.form-control {
            min-height: 120px !important;
            resize: vertical !important;
            color: #fff !important;
            background: rgba(8, 10, 15, 0.8) !important;
        }
        .form-control:focus, input.form-control:focus, textarea.form-control:focus, select.form-control:focus {
            border-color: var(--gold-primary) !important;
            box-shadow: var(--gold-glow) !important;
            background: rgba(8, 10, 15, 0.95) !important;
        }

        /* --- Select2 Gold Theme Custom Styling --- */
        /* --- Real-Time Form Validation Feedback Styling --- */
        .form-control.is-invalid, input.is-invalid, select.is-invalid, textarea.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.35) !important;
            background: rgba(239, 68, 68, 0.08) !important;
        }
        .form-control.is-valid, input.is-valid, select.is-valid, textarea.is-valid {
            border-color: #10b981 !important;
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.25) !important;
        }
        .invalid-feedback-msg {
            color: #f87171;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: fadeIn 0.25s ease forwards;
        }

        /* --- Real-Time Global Search Dropdown Styling --- */
        .search-results-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 380px;
            max-width: 90vw;
            background: rgba(14, 18, 28, 0.96);
            border: 1px solid var(--gold-primary);
            border-radius: 16px;
            backdrop-filter: blur(25px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.8), var(--gold-glow);
            z-index: 99999;
            max-height: 420px;
            overflow-y: auto;
            animation: fadeIn 0.2s ease;
            padding: 8px;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .search-result-item:hover {
            background: rgba(245, 184, 46, 0.15);
            transform: translateX(4px);
        }
        .search-result-icon {
            width: 34px; height: 34px;
            background: rgba(245, 184, 46, 0.12);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gold-light);
            font-size: 0.95rem;
            flex-shrink: 0;
        }
        .valid-feedback-msg {
            color: #34d399;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: fadeIn 0.25s ease forwards;
        }
        .select2-container--default .select2-selection--single {
            background-color: rgba(8, 10, 15, 0.8) !important;
            border: 1px solid var(--border-gold) !important;
            border-radius: 10px !important;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
            font-size: 0.9rem !important;
            padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-dropdown {
            background-color: rgba(14, 18, 28, 0.98) !important;
            border: 1px solid var(--border-gold) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
            z-index: 10005 !important;
        }
        .select2-search__field {
            background-color: rgba(8, 10, 15, 0.9) !important;
            border: 1px solid var(--border-gold) !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
        .select2-results__option {
            color: var(--text-muted) !important;
            font-size: 0.88rem !important;
            padding: 8px 12px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgba(245, 184, 46, 0.2) !important;
            color: var(--gold-light) !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(245, 184, 46, 0.3) !important;
            color: var(--gold-light) !important;
            font-weight: 700 !important;
        }

        /* --- Global Toast Notifications --- */
        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast-msg {
            pointer-events: auto;
            min-width: 280px;
            max-width: 380px;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            background: rgba(18, 24, 38, 0.95);
            border: 1px solid var(--border-gold);
            color: var(--text-main);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.35s ease forwards;
        }
        .toast-msg.toast-success { border-color: #10b981; }
        .toast-msg.toast-success i { color: #34d399; font-size: 1.2rem; }
        .toast-msg.toast-error { border-color: #ef4444; }
        .toast-msg.toast-error i { color: #f87171; font-size: 1.2rem; }

        /* --- Global Custom Confirmation Box --- */
        .custom-modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        .custom-modal-backdrop.show { display: flex; }
        .custom-modal-card {
            background: var(--card-bg);
            border: 1px solid var(--gold-primary);
            border-radius: 20px;
            padding: 2rem;
            width: 90%;
            max-width: 440px;
            text-align: center;
            box-shadow: var(--gold-glow);
            animation: popIn 0.25s ease forwards;
        }
        .custom-modal-icon {
            font-size: 2.8rem;
            color: var(--gold-light);
            margin-bottom: 1rem;
        }

        /* --- Full Mobile Responsive CSS Breakpoints --- */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .close-sidebar-btn { display: block; }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay.show { display: block; }
            .header-search-bar { display: none; }
            .header { padding: 0 1rem; height: 65px; }
            .content-body { padding: 1rem; }
        }

        @media (max-width: 576px) {
            .header-action-btn:nth-child(1),
            .header-action-btn:nth-child(2) {
                display: none;
            }
            .header-user-card {
                padding: 4px 8px;
            }
            .header-user-role {
                display: none;
            }
            .card {
                padding: 1rem;
                border-radius: 14px;
            }
            .custom-table th, .custom-table td {
                padding: 0.65rem 0.5rem;
                font-size: 0.8rem;
            }
            .btn-gold {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
            #toastContainer {
                top: 10px;
                right: 10px;
                left: 10px;
            }
            .toast-msg {
                min-width: unset;
                max-width: 100%;
                font-size: 0.85rem;
                padding: 0.8rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Global Glassmorphic Gold Page Loader -->
    <div id="globalPageLoader" class="global-loader-overlay">
        <div class="global-loader-box">
            <div class="global-loader-spinner"></div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
        </div>
        <div class="global-loader-text">Loading BINALEX...</div>
    </div>

    @php
        $globalPendingDeposits = \App\Models\Deposit::where('status', 'pending')->count();
        $globalPendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
        $globalTotalNotifications = $globalPendingDeposits + $globalPendingWithdrawals;
    @endphp

    <div id="toastContainer"></div>

    <!-- Global Custom Confirmation Modal -->
    <div class="custom-modal-backdrop" id="customConfirmModal">
        <div class="custom-modal-card">
            <div class="custom-modal-icon"><i class="fa-solid fa-circle-question"></i></div>
            <h3 id="modalConfirmTitle" style="color: var(--gold-light); font-size: 1.4rem; margin-bottom: 0.5rem;">Confirm Action</h3>
            <p id="modalConfirmText" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.8rem;">Are you sure you want to proceed with this action?</p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button class="btn-gold" style="background: rgba(255,255,255,0.1); color: var(--text-main);" onclick="closeCustomConfirmModal(false)">Cancel</button>
                <button class="btn-gold" id="modalConfirmBtn" onclick="closeCustomConfirmModal(true)">Confirm & Proceed</button>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay" id="adminSidebarOverlay" onclick="toggleAdminSidebar()"></div>

    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" style="height: 46px; width: 46px; border-radius: 50%; object-fit: cover; box-shadow: 0 0 15px rgba(245, 184, 46, 0.4); border: 1px solid var(--border-gold);">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-weight: 900; font-size: 1.15rem; color: var(--gold-light); letter-spacing: 1px; line-height: 1.1;">BINALEX</span>
                    <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; letter-spacing: 1px; margin-top: 2px;">ADMIN PORTAL</span>
                </div>
            </a>
            <button class="close-sidebar-btn" onclick="toggleAdminSidebar()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="nav-menu-heading"><i class="fa-solid fa-compass"></i> MAIN OVERVIEW</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge" style="color: var(--gold-light);"></i> Dashboard</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <a href="{{ route('admin.packages.index') }}"><i class="fa-solid fa-box-archive" style="color: #34d399;"></i> Staking Packages</a>
            </li>
        </ul>

        <div class="nav-menu-heading"><i class="fa-solid fa-users-gear"></i> USER MANAGEMENT</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-list-ul"></i> All Users List</a>
            </li>
        </ul>

        <div class="nav-menu-heading"><i class="fa-solid fa-chart-line"></i> TRANSACTION REPORTS</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.reports.transactions') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.transactions') }}"><i class="fa-solid fa-receipt"></i> All Transactions</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.roi') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.roi') }}"><i class="fa-solid fa-coins"></i> Daily ROI Report</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.direct') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.direct') }}"><i class="fa-solid fa-bolt"></i> Direct Income Report</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.level') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.level') }}"><i class="fa-solid fa-layer-group"></i> Level Income Report</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.rewards') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.rewards') }}"><i class="fa-solid fa-trophy"></i> Reward Income Report</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.packages') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.packages') }}"><i class="fa-solid fa-box-archive"></i> Package Activations</a>
            </li>
        </ul>

        <div class="nav-menu-heading"><i class="fa-solid fa-sliders"></i> SETTINGS & FINANCIALS</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-sliders" style="color: var(--gold-light);"></i> Token Price & Pool</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                <a href="{{ route('admin.deposits.index') }}" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-wallet" style="color: #38bdf8;"></i> USDT Deposits
                    </div>
                    @if($globalPendingDeposits > 0)
                        <span class="badge-status" style="background: #ef4444; color: #fff; border-radius: 20px; padding: 2px 8px; font-size: 0.68rem; font-weight: 800;">{{ $globalPendingDeposits }} NEW</span>
                    @endif
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                <a href="{{ route('admin.withdrawals.index') }}" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-hand-holding-dollar" style="color: #f87171;"></i> Coin Withdrawals
                    </div>
                    @if($globalPendingWithdrawals > 0)
                        <span class="badge-status" style="background: #ef4444; color: #fff; border-radius: 20px; padding: 2px 8px; font-size: 0.68rem; font-weight: 800;">{{ $globalPendingWithdrawals }} NEW</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="nav-menu-heading"><i class="fa-solid fa-headset"></i> HELP & SUPPORT</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <a href="{{ route('admin.support.index') }}"><i class="fa-solid fa-headset" style="color: #38bdf8;"></i> Support Tickets</a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <!-- Premium Header Bar -->
        <header class="header">
            <div class="header-left">
                <button class="header-toggle-btn" onclick="toggleAdminSidebar()" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div style="position: relative;" class="header-search-bar-wrapper">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="header-search-bar" autocomplete="off">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="adminGlobalSearchInput" name="search" class="header-search-input" placeholder="Real-time search members, tickets, pages, deposits..." value="{{ request('search') }}">
                    </form>
                    <div id="adminGlobalSearchResults" class="search-results-dropdown" style="display: none;"></div>
                </div>
            </div>

            <div class="header-right">
                <button class="header-action-btn" title="Quick Export / Open Site" onclick="window.open('/', '_blank')">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </button>

                <!-- Notification Bell Dropdown -->
                <div style="position: relative;">
                    <button class="header-action-btn" title="Pending Deposit & Withdrawal Notifications" onclick="toggleHeaderNotificationMenu()">
                        <i class="fa-solid fa-bell"></i>
                        @if($globalTotalNotifications > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 900; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #0c101a; box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);">{{ $globalTotalNotifications }}</span>
                        @endif
                    </button>

                    <div class="header-dropdown-menu" id="headerNotificationMenu" style="width: 260px; right: 0; padding: 0.8rem;">
                        <div style="font-size: 0.75rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; margin-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold); padding-bottom: 4px;">
                            <i class="fa-solid fa-bell"></i> PENDING ALERTS
                        </div>

                        <a href="{{ route('admin.deposits.index') }}" class="header-dropdown-item" style="justify-content: space-between;">
                            <span><i class="fa-solid fa-wallet" style="color: #38bdf8;"></i> USDT Deposits</span>
                            <strong style="color: {{ $globalPendingDeposits > 0 ? '#f87171' : '#34d399' }};">{{ $globalPendingDeposits }} Pending</strong>
                        </a>

                        <a href="{{ route('admin.withdrawals.index') }}" class="header-dropdown-item" style="justify-content: space-between;">
                            <span><i class="fa-solid fa-hand-holding-dollar" style="color: #f87171;"></i> Coin Withdrawals</span>
                            <strong style="color: {{ $globalPendingWithdrawals > 0 ? '#f87171' : '#34d399' }};">{{ $globalPendingWithdrawals }} Pending</strong>
                        </a>
                    </div>
                </div>

                <!-- Profile Dropdown Card -->
                <div class="header-user-card" onclick="toggleHeaderProfileMenu()">
                    <div class="header-user-avatar">AD</div>
                    <div class="header-user-info">
                        <span class="header-user-name">Administrator</span>
                        <span class="header-user-role">System Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; color: var(--gold-light); margin-left: 4px;"></i>

                    <div class="header-dropdown-menu" id="headerProfileMenu">
                        <a href="{{ route('admin.dashboard') }}" class="header-dropdown-item"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                        <a href="{{ route('admin.profile.index') }}" class="header-dropdown-item"><i class="fa-solid fa-user-gear"></i> Manage Profile & Password</a>
                        <a href="{{ route('admin.packages.index') }}" class="header-dropdown-item"><i class="fa-solid fa-box-archive"></i> Staking Packages</a>
                        <a href="{{ route('admin.settings.index') }}" class="header-dropdown-item"><i class="fa-solid fa-sliders"></i> Token Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="header-dropdown-item" style="color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Logout Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

    <!-- jQuery & Select2 JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // --- Global Page Loader Handling ---
        window.addEventListener('load', () => {
            const loader = document.getElementById('globalPageLoader');
            if (loader) loader.classList.add('hidden');
        });

        function toggleAdminSidebar() {
            if (window.innerWidth <= 992) {
                document.getElementById('adminSidebar').classList.toggle('show');
                document.getElementById('adminSidebarOverlay').classList.toggle('show');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
        }

        function toggleHeaderProfileMenu() {
            const menu = document.getElementById('headerProfileMenu');
            menu.classList.toggle('show');
        }

        document.addEventListener('click', (e) => {
            const card = document.querySelector('.header-user-card');
            const menu = document.getElementById('headerProfileMenu');
            if (card && menu && !card.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        // --- Global Toast Notification System ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-msg toast-${type}`;
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'fadeOutRight 0.35s ease forwards';
                setTimeout(() => toast.remove(), 350);
            }, 3500);
        }

        function hideGlobalLoader() {
            const loader = document.getElementById('globalPageLoader');
            if (loader) {
                loader.classList.add('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', hideGlobalLoader);
        window.addEventListener('load', hideGlobalLoader);
        setTimeout(hideGlobalLoader, 300);

        // Trigger Laravel Flash Messages via Toasts
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $err)
                showToast(@json($err), 'error');
            @endforeach
        @endif

        function copyToClipboard(text, label) {
            navigator.clipboard.writeText(text);
            showToast(label + ' (' + text + ') copied to clipboard!', 'success');
        }

        // --- Global Custom Confirm Dialog ---
        let pendingConfirmResolver = null;
        function customConfirm(title, text) {
            document.getElementById('modalConfirmTitle').innerText = title || 'Confirm Action';
            document.getElementById('modalConfirmText').innerText = text || 'Are you sure you want to proceed?';
            document.getElementById('customConfirmModal').classList.add('show');

            return new Promise((resolve) => {
                pendingConfirmResolver = resolve;
            });
        }

        function closeCustomConfirmModal(result) {
            document.getElementById('customConfirmModal').classList.remove('show');
            if (pendingConfirmResolver) {
                pendingConfirmResolver(result);
                pendingConfirmResolver = null;
            }
        }

        // --- Real-time Interactive Client Side Validation, Select2 & Global Loader Trigger ---
        $(document).ready(function() {
            // Auto attach interactive eye toggles on all password fields
            attachPasswordEyeToggles();

            // Auto initialize Select2 on all select elements
            $('select').select2({
                width: '100%',
                placeholder: 'Select an option'
            });

            document.querySelectorAll('form').forEach(form => {
                const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        input.dataset.touched = 'true';
                        validateField(input);
                    });
                    ['input', 'keyup', 'change'].forEach(evt => {
                        input.addEventListener(evt, () => {
                            if (input.dataset.touched === 'true') {
                                validateField(input);
                            }
                        });
                    });
                });

                form.addEventListener('submit', async (e) => {
                    let isValid = true;
                    inputs.forEach(input => {
                        input.dataset.touched = 'true';
                        if (!validateField(input, true)) isValid = false;
                    });

                    if (!isValid) {
                        e.preventDefault();
                        showToast('Please fix validation errors highlighted in red before submitting.', 'error');
                        return;
                    }

                    // Attach confirm dialog if form has data-confirm attribute
                    if (form.hasAttribute('data-confirm')) {
                        e.preventDefault();
                        const confirmMsg = form.getAttribute('data-confirm');
                        const confirmed = await customConfirm('Confirmation', confirmMsg);
                        if (confirmed) {
                            form.removeAttribute('data-confirm');
                            const loader = document.getElementById('globalPageLoader');
                            if (loader && form.target !== '_blank') loader.classList.remove('hidden');
                            form.submit();
                        }
                    } else {
                        const loader = document.getElementById('globalPageLoader');
                        if (loader && form.target !== '_blank') loader.classList.remove('hidden');
                    }
                });
            });

            // Real-Time Global Search Engine JS
            const searchInput = document.getElementById('adminGlobalSearchInput');
            const searchResults = document.getElementById('adminGlobalSearchResults');
            const searchApiUrl = "{{ route('admin.global-search') }}";

            if (searchInput && searchResults) {
                let searchTimeout = null;

                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.trim();
                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        searchResults.style.display = 'none';
                        searchResults.innerHTML = '';
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        searchResults.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--gold-light);"><i class="fa-solid fa-spinner fa-spin"></i> Searching...</div>';
                        searchResults.style.display = 'block';

                        fetch(`${searchApiUrl}?q=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (!data.results || data.results.length === 0) {
                                    searchResults.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.88rem;">No results found for "<strong>' + query + '</strong>"</div>';
                                } else {
                                    let html = '';
                                    data.results.forEach(item => {
                                        html += `
                                            <a href="${item.url}" class="search-result-item">
                                                <div class="search-result-icon"><i class="fa-solid ${item.icon}"></i></div>
                                                <div style="flex-grow: 1; overflow: hidden;">
                                                    <div style="font-weight: 700; font-size: 0.88rem; color: #fff; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">${item.title}</div>
                                                    <div style="font-size: 0.76rem; color: var(--text-muted); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">${item.subtitle} • <span style="color: var(--gold-light); font-weight: 600;">${item.category}</span></div>
                                                </div>
                                                <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem; color: var(--text-muted);"></i>
                                            </a>
                                        `;
                                    });
                                    searchResults.innerHTML = html;
                                }
                            })
                            .catch(() => {
                                searchResults.style.display = 'none';
                            });
                    }, 250);
                });

                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.style.display = 'none';
                    }
                });
            }

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

        function validateField(input, force = false) {
            if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button' || input.readOnly || !input.offsetParent) return true;
            if (!force && input.dataset.touched !== 'true') return true;

            let isValid = true;
            let msg = '';
            const value = input.value.trim();
            const labelEl = input.parentNode.querySelector('label') || input.closest('.form-group')?.querySelector('label');
            const fieldName = labelEl ? labelEl.innerText.replace('*', '').trim() : (input.getAttribute('placeholder') || input.name || 'Field');

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
            } else if ((input.type === 'password' || input.name === 'password') && value && input.hasAttribute('required')) {
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
            } else if (input.type === 'number' && value) {
                const num = parseFloat(value);
                const min = input.getAttribute('min') ? parseFloat(input.getAttribute('min')) : null;
                if (isNaN(num)) {
                    isValid = false;
                    msg = 'Please enter a valid number.';
                } else if (min !== null && num < min) {
                    isValid = false;
                    msg = `Value must be at least ${min}.`;
                }
            }

            // Find feedback container
            const wrapper = input.closest('.password-wrapper') || input.parentNode;
            const targetContainer = input.closest('.password-wrapper') ? input.closest('.password-wrapper').parentNode : wrapper;
            
            let existingMsg = targetContainer.querySelector('.invalid-feedback-msg, .valid-feedback-msg');
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

        function attachPasswordEyeToggles() {
            document.querySelectorAll('input[type="password"]').forEach(input => {
                if (input.dataset.eyeAttached === 'true') return;
                input.dataset.eyeAttached = 'true';

                let wrapper = input.closest('.password-wrapper');
                if (!wrapper) {
                    wrapper = document.createElement('div');
                    wrapper.className = 'password-wrapper';
                    wrapper.style.position = 'relative';
                    wrapper.style.width = '100%';
                    input.parentNode.insertBefore(wrapper, input);
                    wrapper.appendChild(input);
                }

                input.style.paddingRight = '2.5rem';

                let eye = wrapper.querySelector('.password-toggle-icon, .auto-password-toggle-eye');
                if (!eye) {
                    eye = document.createElement('i');
                    eye.className = 'fa-solid fa-eye auto-password-toggle-eye';
                    eye.style.cssText = 'position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--gold-light); z-index: 10; font-size: 0.95rem;';
                    wrapper.appendChild(eye);
                }

                eye.addEventListener('click', () => {
                    if (input.type === 'password') {
                        input.type = 'text';
                        eye.classList.remove('fa-eye');
                        eye.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        eye.classList.remove('fa-eye-slash');
                        eye.classList.add('fa-eye');
                    }
                });
            });
        }

        function toggleHeaderNotificationMenu() {
            const menu = document.getElementById('headerNotificationMenu');
            const profileMenu = document.getElementById('headerProfileMenu');
            if (profileMenu) profileMenu.classList.remove('show');
            if (menu) menu.classList.toggle('show');
        }
    </script>
</body>
</html>
