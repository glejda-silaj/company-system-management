<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaNet — ISP Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f1117;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(26,122,74,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,122,74,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }
        .bg-glow {
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(26,122,74,0.15) 0%, transparent 70%);
            top: -100px; left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            pointer-events: none;
        }

        /* Navbar */
        .top-nav {
            position: relative; z-index: 10;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #1a7a4a, #22c55e);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }
        .brand-text {
            font-size: 1.1rem; font-weight: 700; color: #fff;
            letter-spacing: 0.5px;
        }
        .brand-text span { color: #4ade80; }

        .nav-login {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 20px;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            color: rgba(255,255,255,0.8);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-login:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-color: rgba(255,255,255,0.3);
        }

        /* Hero */
        .hero-section {
            position: relative; z-index: 5;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 5rem 1.5rem 4rem;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(26,122,74,0.15);
            border: 1px solid rgba(26,122,74,0.35);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px; font-weight: 600;
            color: #4ade80;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1.75rem;
        }
        .hero-badge i { font-size: 14px; }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            max-width: 720px;
            margin-bottom: 1.25rem;
        }
        .hero-title .highlight {
            background: linear-gradient(135deg, #1a7a4a, #4ade80);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.45);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .hero-actions {
            display: flex; align-items: center; gap: 12px;
            flex-wrap: wrap; justify-content: center;
        }

        .btn-primary-custom {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 28px;
            background: linear-gradient(135deg, #1a7a4a, #22c55e);
            color: #fff; font-size: 15px; font-weight: 600;
            border-radius: 10px; text-decoration: none;
            box-shadow: 0 4px 20px rgba(26,122,74,0.35);
            transition: all 0.2s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26,122,74,0.45);
            color: #fff;
        }

        /* Feature Cards */
        .features-section {
            position: relative; z-index: 5;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1.5rem 5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .feature-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 1.5rem;
            transition: all 0.2s;
        }
        .feature-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(26,122,74,0.3);
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 1rem;
        }
        .fi-green  { background: rgba(26,122,74,0.2);  color: #4ade80; }
        .fi-blue   { background: rgba(29,78,216,0.2);  color: #60a5fa; }
        .fi-purple { background: rgba(124,58,237,0.2); color: #a78bfa; }

        .feature-title {
            font-size: 14px; font-weight: 600;
            color: #fff; margin-bottom: 6px;
        }
        .feature-desc {
            font-size: 13px; color: rgba(255,255,255,0.4);
            line-height: 1.6;
        }

        /* Stats bar */
        .stats-bar {
            position: relative; z-index: 5;
            display: flex; justify-content: center; gap: 3rem;
            padding: 2rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 3rem;
        }
        .stat-item { text-align: center; }
        .stat-num {
            font-size: 1.6rem; font-weight: 700; color: #fff;
        }
        .stat-num span { color: #4ade80; }
        .stat-lbl {
            font-size: 12px; color: rgba(255,255,255,0.35);
            margin-top: 2px;
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow"></div>

<!-- Navbar -->
<nav class="top-nav">
    <a href="#" class="nav-brand">
        <div class="brand-icon"><i class="ti ti-globe"></i></div>
        <div class="brand-text">Nova<span>Net</span></div>
    </a>
    <a href="login.php" class="nav-login">
        <i class="ti ti-login"></i> Hyr në sistem
    </a>
</nav>

<!-- Hero -->
<section class="hero-section">
    <div class="hero-badge">
        <i class="ti ti-wifi"></i> ISP Management System
    </div>

    <h1 class="hero-title">
        Menaxho rrjetin tënd<br>
        <span class="highlight">me lehtësi dhe shpejtësi</span>
    </h1>

    <p class="hero-sub">
        Platforma gjithëpërfshirëse për menaxhimin e klientëve,
        paketave të internetit dhe abonimeve — gjithçka në një vend.
    </p>

    <div class="hero-actions">
        <a href="login.php" class="btn-primary-custom">
            <i class="ti ti-login"></i> Hyr në Dashboard
        </a>
    </div>
</section>

<!-- Stats -->
<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-num"><span>∞</span></div>
        <div class="stat-lbl">Klientë</div>
    </div>
    <div class="stat-item">
        <div class="stat-num"><span>24</span>/7</div>
        <div class="stat-lbl">Monitorim</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">100<span>%</span></div>
        <div class="stat-lbl">Kontroll</div>
    </div>
</div>

<!-- Features -->
<section class="features-section">
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon fi-blue">
                <i class="ti ti-users"></i>
            </div>
            <div class="feature-title">Menaxhim Klientësh</div>
            <div class="feature-desc">Shto, edito dhe menaxho të gjithë klientët e rrjetit tënd në një vend.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-green">
                <i class="ti ti-box"></i>
            </div>
            <div class="feature-title">Paketa Interneti</div>
            <div class="feature-desc">Krijo paketa me shpejtësi dhe çmime të ndryshme sipas nevojave.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-purple">
                <i class="ti ti-file-invoice"></i>
            </div>
            <div class="feature-title">Abonime</div>
            <div class="feature-desc">Gjurmo abonimet aktive dhe joaktive me filtra dhe raporte.</div>
        </div>
    </div>
</section>

<script src="assets/js/script.js"></script>
</body>
</html>