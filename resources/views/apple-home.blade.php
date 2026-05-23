<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f7;
            --bg-tertiary: #ffffff;
            --text-primary: #1d1d1f;
            --text-secondary: #86868b;
            --border-color: rgba(0, 0, 0, 0.08);
            --card-bg: rgba(255, 255, 255, 0.8);
            --card-border: rgba(0, 0, 0, 0.06);
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 8px 40px rgba(0, 0, 0, 0.1);
            --accent: #0071e3;
            --accent-hover: #0077ed;
            --button-bg: #0071e3;
            --button-text: #ffffff;
            --button-secondary-bg: rgba(0, 113, 227, 0.08);
            --button-secondary-text: #0071e3;
            --toggle-bg: #e8e8ed;
            --toggle-icon: #1d1d1f;
            --glow: rgba(0, 113, 227, 0.15);
        }

        [data-theme="dark"] {
            --bg-primary: #000000;
            --bg-secondary: #0a0a0a;
            --bg-tertiary: #1d1d1f;
            --text-primary: #f5f5f7;
            --text-secondary: #86868b;
            --border-color: rgba(255, 255, 255, 0.1);
            --card-bg: rgba(29, 29, 31, 0.8);
            --card-border: rgba(255, 255, 255, 0.08);
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
            --shadow-hover: 0 8px 40px rgba(0, 0, 0, 0.6);
            --accent: #2997ff;
            --accent-hover: #47a6ff;
            --button-bg: #2997ff;
            --button-text: #000000;
            --button-secondary-bg: rgba(41, 151, 255, 0.15);
            --button-secondary-text: #2997ff;
            --toggle-bg: #424245;
            --toggle-icon: #f5f5f7;
            --glow: rgba(41, 151, 255, 0.25);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--toggle-bg);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow);
        }

        .theme-toggle:hover {
            transform: scale(1.08);
            box-shadow: var(--shadow-hover);
        }

        .theme-toggle svg {
            width: 22px;
            height: 22px;
            fill: var(--toggle-icon);
            transition: fill 0.3s ease, transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .theme-toggle:hover svg {
            transform: rotate(15deg);
        }

        .theme-toggle .sun-icon { display: block; }
        .theme-toggle .moon-icon { display: none; }

        [data-theme="dark"] .theme-toggle .sun-icon { display: none; }
        [data-theme="dark"] .theme-toggle .moon-icon { display: block; }

        /* Navigation */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 16px 24px;
            background: rgba(var(--bg-primary), 0.8);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 980px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-link {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.2s ease;
            letter-spacing: -0.1px;
        }

        .nav-link:hover {
            color: var(--accent);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 24px 80px;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, var(--glow) 0%, transparent 50%);
            opacity: 0.6;
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--button-secondary-bg);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .hero-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px var(--glow);
        }

        .hero-badge-dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        .hero h1 {
            font-size: clamp(48px, 10vw, 96px);
            font-weight: 800;
            letter-spacing: -3px;
            line-height: 1.05;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(18px, 3vw, 24px);
            font-weight: 400;
            color: var(--text-secondary);
            max-width: 580px;
            line-height: 1.5;
            margin-bottom: 40px;
            letter-spacing: -0.3px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 980px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            letter-spacing: -0.2px;
        }

        .btn-primary {
            background: var(--button-bg);
            color: var(--button-text);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--glow);
        }

        .btn-secondary {
            background: var(--button-secondary-bg);
            color: var(--button-secondary-text);
        }

        .btn-secondary:hover {
            background: var(--button-secondary-bg);
            filter: brightness(0.95);
            transform: translateY(-2px);
        }

        /* Features Section */
        .features {
            padding: 100px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .features-header h2 {
            font-size: clamp(32px, 6vw, 56px);
            font-weight: 700;
            letter-spacing: -1.5px;
            margin-bottom: 16px;
        }

        .features-header p {
            font-size: clamp(16px, 2vw, 20px);
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            background: var(--button-secondary-bg);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .feature-card h3 {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.4px;
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border-color);
            padding: 40px 24px;
            text-align: center;
        }

        .footer p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                padding: 12px 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .hero {
                padding: 100px 20px 60px;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
                max-width: 280px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .features {
                padding: 60px 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .theme-toggle {
                top: auto;
                bottom: 20px;
                right: 20px;
            }
        }

        /* Animation on load */
        .hero > * {
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        .hero h1 { animation-delay: 0.1s; }
        .hero-subtitle { animation-delay: 0.2s; }
        .hero-badge { animation-delay: 0s; }
        .hero-actions { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .feature-card:nth-child(1) { animation-delay: 0.4s; }
        .feature-card:nth-child(2) { animation-delay: 0.5s; }
        .feature-card:nth-child(3) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2"/>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
        </svg>
    </button>

    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <a href="/" class="nav-logo">Coolify</a>
            <div class="nav-links">
                @auth
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                @else
                    <a href="/login" class="nav-link">Sign In</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Now Available
        </div>
        <h1>Welcome to Coolify</h1>
        <p class="hero-subtitle">
            Deploy your applications with ease. A beautiful, self-hosted platform that simplifies your workflow.
        </p>
        <div class="hero-actions">
            @auth
                <a href="/dashboard" class="btn btn-primary">
                    Go to Dashboard →
                </a>
            @else
                <a href="/register" class="btn btn-primary">
                    Get Started Free
                </a>
                <a href="/login" class="btn btn-secondary">
                    Sign In
                </a>
            @endauth
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-header">
            <h2>Everything you need</h2>
            <p>Powerful features to manage and deploy your applications</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Fast Deployments</h3>
                <p>Deploy your applications in seconds with our optimized infrastructure and smart caching.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Secure by Default</h3>
                <p>Enterprise-grade security with SSL certificates, automated backups, and isolation.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Real-time Monitoring</h3>
                <p>Monitor your applications with detailed analytics and instant alerts.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2024 Coolify. Built with Laravel.</p>
    </footer>

    <script>
        // Theme Management
        function getPreferredTheme() {
            const stored = localStorage.getItem('theme');
            if (stored) return stored;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        }

        // Initialize theme on page load
        setTheme(getPreferredTheme());

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                setTheme(e.matches ? 'dark' : 'light');
            }
        });
    </script>
</body>
</html>
