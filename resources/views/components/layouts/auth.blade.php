<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cyber Sentinel') }} | @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/themes.css'])

    <style>
        .auth-background {
            background: linear-gradient(135deg, #0a192f 0%, #1a1a2e 50%, #16213e 100%);
            position: relative;
            overflow: hidden;
        }
        .auth-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(100, 255, 218, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100, 255, 218, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(40px, 40px); }
        }
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
        }
        .glow-orb-1 {
            width: 400px;
            height: 400px;
            background: #64ffda;
            top: -100px;
            left: -100px;
        }
        .glow-orb-2 {
            width: 300px;
            height: 300px;
            background: #b9c7e4;
            bottom: -50px;
            right: -50px;
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }
    </style>
</head>
<body class="auth-background min-h-screen flex items-center justify-center p-4">
    <!-- Background Effects -->
    <div class="glow-orb glow-orb-1"></div>
    <div class="glow-orb glow-orb-2"></div>

    <!-- Auth Container -->
    <div class="relative z-10 w-full max-w-md">
        {{ $slot }}
    </div>

    @stack('scripts')
</body>
</html>
