<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'System Notification') - BINALEX</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            --bg-dark: #07090e;
            --gold-primary: #f5b82e;
            --gold-light: #ffd700;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #f5b82e 50%, #c88909 100%);
            --gold-glow: 0 0 35px rgba(245, 184, 46, 0.35);
            --border-gold: rgba(245, 184, 46, 0.3);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background: linear-gradient(135deg, rgba(7, 9, 14, 0.82) 0%, rgba(12, 16, 26, 0.92) 100%), url('{{ asset("images/auth_bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .error-card {
            background: rgba(14, 18, 28, 0.92);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 540px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(25px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.8), var(--gold-glow);
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--gold-gradient);
        }

        .error-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 30px rgba(245, 184, 46, 0.5);
            border: 2px solid var(--gold-light);
            margin: 0 auto 1.5rem auto;
            animation: logoFloat 4s infinite ease-in-out;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .error-code {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .error-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
        }

        .error-message {
            color: var(--text-muted);
            font-size: 0.98rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #000;
            padding: 0.75rem 1.6rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.92rem;
            text-decoration: none;
            box-shadow: var(--gold-glow);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px rgba(245, 184, 46, 0.6);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--border-gold);
            padding: 0.75rem 1.6rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.92rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            border-color: var(--gold-light);
            color: var(--gold-light);
            background: rgba(245, 184, 46, 0.1);
        }

        @media (max-width: 480px) {
            .error-card { padding: 2.2rem 1.5rem; }
            .error-code { font-size: 3.8rem; }
            .error-title { font-size: 1.3rem; }
            .error-actions { flex-direction: column; }
            .error-actions a { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <img src="{{ asset('images/logo.png') }}" alt="Binalex Logo" class="error-logo">
        <div class="error-code">@yield('code', '404')</div>
        <h1 class="error-title">@yield('heading', 'PAGE NOT FOUND')</h1>
        <p class="error-message">@yield('message', 'The requested page could not be located in BINALEX ecosystem.')</p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn-gold"><i class="fa-solid fa-house"></i> Home Page</a>
            <a href="javascript:history.back()" class="btn-outline"><i class="fa-solid fa-arrow-left"></i> Go Back</a>
        </div>
    </div>
</body>
</html>
