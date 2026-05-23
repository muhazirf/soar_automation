<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello World</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            --bg-start: #09090b;
            --bg-mid: #111827;
            --bg-end: #1f2937;
            --panel: rgba(15, 23, 42, 0.72);
            --border: rgba(255,255,255,0.12);
            --text: #f8fafc;
            --muted: #cbd5e1;
            --accent: #ffffff;
            --shadow: 0 30px 80px rgba(15, 23, 42, 0.45);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.12), transparent 22%),
                linear-gradient(135deg, var(--bg-start), var(--bg-mid) 45%, var(--bg-end));
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            inset: auto;
            border-radius: 999px;
            filter: blur(8px);
            opacity: 0.55;
        }

        body::before {
            width: 16rem;
            height: 16rem;
            top: 6rem;
            left: 8rem;
            background: rgba(255,255,255,0.1);
        }

        body::after {
            width: 20rem;
            height: 20rem;
            bottom: 4rem;
            right: 8rem;
            background: rgba(255,255,255,0.06);
        }

        .shell {
            position: relative;
            z-index: 1;
            width: min(100%, 960px);
            padding: 2rem;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 32px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px);
            padding: clamp(2rem, 4vw, 3.5rem);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            color: var(--muted);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .eyebrow .dot {
            width: 0.6rem;
            height: 0.6rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #fff, #cbd5e1);
        }

        h1 {
            margin: 0;
            font-size: clamp(3rem, 5vw, 4.5rem);
            line-height: 0.95;
            letter-spacing: -0.07em;
            font-weight: 800;
        }

        .subtitle {
            margin: 1rem 0 0;
            max-width: 38rem;
            color: var(--muted);
            font-size: 1.06rem;
            line-height: 1.7;
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.95rem 1.3rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.94);
            color: #0f172a;
            font-weight: 700;
            text-decoration: none;
            transition: transform 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        .pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(255,255,255,0.18);
        }

        .pill.secondary {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .stats {
            margin-top: 2.25rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .stat {
            padding: 1rem 1.1rem;
            border-radius: 22px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .stat strong {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 0.4rem;
        }

        .stat span {
            color: var(--muted);
            font-size: 0.95rem;
        }

        @media (max-width: 640px) {
            .shell { padding: 1rem; }
            .card { border-radius: 24px; }
            h1 { font-size: 2.5rem; }
            .subtitle { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="card">
            <p class="eyebrow"><span class="dot"></span> Apple-inspired landing</p>
            <h1>Hello World</h1>
            <p class="subtitle">
                Halaman default kini diganti dengan tampilan minimalis, premium, dan modern dengan sentuhan estetika Apple.
            </p>
            <div class="cta-row">
                <a href="/dashboard" class="pill">Masuk Dashboard</a>
                <a href="/login" class="pill secondary">Login</a>
            </div>
            <div class="stats">
                <div class="stat">
                    <strong>Minimal</strong>
                    <span>Desain bersih, fokus pada pesan utama.</span>
                </div>
                <div class="stat">
                    <strong>Premium</strong>
                    <span>Palet gelap, kaca blur, dan bayangan halus.</span>
                </div>
                <div class="stat">
                    <strong>Responsive</strong>
                    <span>Tampil menawan di desktop maupun mobile.</span>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
