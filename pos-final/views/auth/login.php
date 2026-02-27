<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – <?= COMPANY_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-color: #453dde;
            --brand-dark: #1a1a2e;
        }

        body { 
            font-family: 'Outfit', sans-serif;
            background-color: white;
            min-height: 100vh; 
            color: #000000; 
        }

        /* ── Intro overlay ── */
        #intro-overlay {
            position: fixed;
            inset: 0;
            background: var(--brand-color);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: overlayReveal 0.6s cubic-bezier(0.77, 0, 0.18, 1) 1.2s forwards;
        }

        .intro-logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            animation: introLogoFadeOut 0.4s ease 1s forwards;
        }

        .intro-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            animation: iconPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        .intro-text {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            animation: introTextSlide 0.5s cubic-bezier(0.34, 1.2, 0.64, 1) 0.4s both;
        }

        .intro-sub {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: introTextSlide 0.5s cubic-bezier(0.34, 1.2, 0.64, 1) 0.55s both;
        }

        .intro-bar {
            width: 180px;
            height: 3px;
            background: rgba(255,255,255,0.2);
            border-radius: 99px;
            overflow: hidden;
            animation: introTextSlide 0.5s ease 0.65s both;
        }

        .intro-bar-fill {
            height: 100%;
            background: rgba(255,255,255,0.9);
            border-radius: 99px;
            animation: barFill 0.9s cubic-bezier(0.4, 0, 0.2, 1) 0.7s both;
        }

        @keyframes overlayReveal {
            to { clip-path: inset(0 0 100% 0); pointer-events: none; }
        }

        @keyframes iconPop {
            from { transform: scale(0.4) rotate(-10deg); opacity: 0; }
            to   { transform: scale(1) rotate(0); opacity: 1; }
        }

        @keyframes introTextSlide {
            from { transform: translateY(14px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        @keyframes introLogoFadeOut {
            to { opacity: 0; transform: scale(0.95); }
        }

        @keyframes barFill {
            from { width: 0; }
            to   { width: 100%; }
        }

        /* ── Page content animations ── */
        .card {
            background: rgb(255, 255, 255); 
            border: 1px solid rgba(0, 0, 0, 0.1) !important; 
            backdrop-filter: blur(25px); 
            border-radius: 24px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(30px);
            animation: cardEntrance 0.7s cubic-bezier(0.34, 1.2, 0.64, 1) 1.5s forwards;
        }

        @keyframes cardEntrance {
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-item {
            opacity: 0;
            transform: translateY(16px);
            animation: itemFadeUp 0.5s ease forwards;
        }

        .animate-item:nth-child(1) { animation-delay: 1.75s; }
        .animate-item:nth-child(2) { animation-delay: 1.9s; }
        .animate-item:nth-child(3) { animation-delay: 2.05s; }
        .animate-item:nth-child(4) { animation-delay: 2.2s; }
        .animate-item:nth-child(5) { animation-delay: 2.35s; }
        .animate-item:nth-child(6) { animation-delay: 2.45s; }

        @keyframes itemFadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Original styles ── */
        .form-control { 
            background: rgba(255, 255, 255, 0.05) !important; 
            border: 1px solid rgb(0, 0, 0) !important; 
            color: #000000 !important; 
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus { 
            border-color: var(--brand-color) !important; 
            box-shadow: 0 0 0 0.25rem rgba(69, 61, 222, 0.2) !important; 
            background: rgba(255, 255, 255, 0.08) !important;
        }

        .form-control::placeholder { color: rgba(0, 0, 0, 0.21) !important; }

        label { 
            color: rgb(0, 0, 0); 
            font-weight: 500;
            margin-left: 5px;
            font-size: 0.9rem;
        }

        .logo {
            width: 220px;
            height: 80px;
            object-fit: contain;
        }

        .btn-brand {
            background-color: var(--brand-color);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: transform 0.2s, background-color 0.2s;
        }

        .btn-brand:hover {
            background-color: #564ff0;
            color: white;
            transform: translateY(-2px);
        }

        .btn-brand:active { transform: translateY(0); }

        h4 { letter-spacing: -0.5px; }

        .hint { color: rgb(0, 0, 0); font-size: 0.75rem; }
        .text-brand { color: var(--brand-color) !important; }
    </style>
</head>
<body class="d-flex align-items-center">

<!-- ══ Intro Overlay ══ -->
<div id="intro-overlay">
    <div class="intro-logo-wrap">
        <div class="intro-icon">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="intro-text">Système POS</div>
        <div class="intro-sub">Solutions Technologiques</div>
        <div class="intro-bar"><div class="intro-bar-fill"></div></div>
    </div>
</div>

<!-- ══ Main Content ══ -->
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
    <div class="card p-4 shadow-lg mt-5">
        <div class="text-center mb-4 animate-item">
            <img src="public/images/logo.png"
                 alt="Logo <?= COMPANY_NAME ?>"
                 class="logo"
                 onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
            <h4 class="fw-bold mb-0">Système POS</h4>
            <p class="text-black-50 small">Solutions Technologiques</p>
        </div>

        <?php if (!empty($loginError)): ?>
        <div class="alert alert-danger py-2 small rounded-3 border-0 animate-item"
             style="background: rgba(220, 53, 70, 0.647); color: #ffffff;">
            <i class="fas fa-exclamation-circle me-2"></i><?= $loginError ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login&action=post">
            <div class="mb-3 animate-item">
                <label class="form-label">Identifiant</label>
                <input type="text" name="login" class="form-control" placeholder="Nom d'utilisateur"
                       value="<?= sanitize($_POST['login'] ?? '') ?>" autocomplete="username" required>
            </div>
            <div class="mb-4 animate-item">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••"
                       autocomplete="current-password" required>
            </div>
            <div class="animate-item">
                <button type="submit" class="btn btn-brand w-100 shadow-sm">
                    Se connecter <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

        <p class="hint text-center mt-4 animate-item">
            Système de gestion sécurisé v2.0
        </p>
    </div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Remove overlay from DOM after animation completes so it doesn't block interaction
    document.getElementById('intro-overlay').addEventListener('animationend', function(e) {
        if (e.animationName === 'overlayReveal') {
            this.remove();
        }
    });
</script>
</body>
</html>