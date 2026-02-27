<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'POS' ?> – <?= COMPANY_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="public/css/app.css" rel="stylesheet">
    <style>
        :root {
            --brand:       #453dde;
            --brand-dark:  #2d27a8;
            --brand-light: #eeecfd;
            --brand-mid:   #c7c3f7;
            --border:      #e8e6fb;
            --text:        #1a1740;
            --text-muted:  #6b6897;
            --surface:     #ffffff;
        }

        /* ── Navbar shell ── */
        .pos-navbar {
            background: var(--surface) !important;
            border-bottom: 1.5px solid var(--border);
            padding: 0 0;
            box-shadow: 0 2px 16px rgba(69, 61, 222, 0.07);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* ── Brand / logo area ── */
        .pos-navbar .navbar-brand {
            padding: 0;
            margin-right: 32px;
            border-right: 1.5px solid var(--border);
            padding-right: 28px;
            display: flex;
            align-items: center;
        }

        /* ── Nav links ── */
        .pos-navbar .nav-link {
            color: var(--text-muted) !important;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 22px 14px !important;
            border-bottom: 3px solid transparent;
            border-top: 3px solid transparent;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
        }

        .pos-navbar .nav-link i {
            font-size: 0.85rem;
            transition: transform 0.2s;
        }

        .pos-navbar .nav-link:hover {
            color: var(--brand) !important;
            background: var(--brand-light);
        }

        .pos-navbar .nav-link:hover i {
            transform: scale(1.15);
        }

        .pos-navbar .nav-link.active {
            color: var(--brand) !important;
            border-bottom-color: var(--brand) !important;
            background: var(--brand-light);
        }

        /* ── Right side: user chip ── */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            background: var(--brand-light);
            border: 1.5px solid var(--border);
            border-radius: 99px;
            padding: 6px 14px 6px 8px;
            font-size: 0.82rem;
            color: var(--text);
            font-weight: 600;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: var(--brand);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 800;
            flex-shrink: 0;
            letter-spacing: 0.5px;
        }

        .user-role-badge {
            background: var(--brand-mid);
            color: var(--brand-dark);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ── Logout button ── */
        .btn-logout {
            background: transparent !important;
            border: 1.5px solid #fca5a5 !important;
            color: #dc2626 !important;
            border-radius: 10px !important;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 7px 14px;
            transition: background 0.2s, color 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: #fee2e2 !important;
            transform: translateY(-1px);
        }

        /* ── Mobile toggler ── */
        .pos-navbar .navbar-toggler {
            border-color: var(--border) !important;
            color: var(--brand) !important;
        }
        .pos-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23453dde' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        /* ── Dropdowns ── */
        .pos-navbar .dropdown-menu {
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(69, 61, 222, 0.12);
            padding: 8px;
            margin-top: -2px !important;
            animation: navDrop 0.2s ease-out;
        }
        @keyframes navDrop {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .pos-navbar .dropdown-item {
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.15s;
        }
        .pos-navbar .dropdown-item i {
            width: 16px;
            text-align: center;
            font-size: 0.8rem;
        }
        .pos-navbar .dropdown-item:hover, .pos-navbar .dropdown-item.active {
            background: var(--brand-light);
            color: var(--brand) !important;
        }
        .pos-navbar .dropdown-toggle::after {
            font-family: "Font Awesome 6 Free";
            content: "\f107";
            font-weight: 900;
            border: none;
            vertical-align: middle;
            margin-left: 6px;
            font-size: 0.75rem;
            transition: transform 0.2s;
        }
        .pos-navbar .show > .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .btn-navbar-primary {
            background: var(--brand);
            color: #fff !important;
            border-radius: 10px;
            font-weight: 700;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
            box-shadow: 0 4px 12px var(--brand-glow);
        }
        .btn-navbar-primary:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(69, 61, 222, 0.2);
        }
        .btn-navbar-primary i {
            font-size: 0.9rem;
        }

        /* ── Flash alert ── */
        .flash-bar {
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
        }

        /* ── Page content ── */
        body {
            background: #f5f4ff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg pos-navbar">
    <div class="container-fluid px-4">

        <!-- Logo -->
        <a class="navbar-brand" href="index.php?page=dashboard">
            <img src="public/images/logo.png"
                 alt="Logo <?= COMPANY_NAME ?>"
                 style="width: 160px; height: 52px; object-fit: contain;"
                 onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
            <div id="logo-fallback"
                 style="display:none; align-items:center; gap:10px;">
                <div style="width:36px;height:36px;background:var(--brand);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1rem;">P</div>
                <span style="color:var(--text);font-weight:800;font-size:1rem;letter-spacing:-0.4px;"><?= COMPANY_NAME ?></span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <!-- Nav items -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['page'] ?? '') === 'dashboard' ? 'active' : '' ?>"
                       href="index.php?page=dashboard">
                        <i class="fas fa-tachometer-alt"></i>Tableau de bord
                    </a>
                </li>

                <?php 
                $page = $_GET['page'] ?? '';
                $ventesActive = in_array($page, ['pos', 'history', 'credits', 'caisse', 'clients']);
                $inventaireActive = in_array($page, ['produits', 'categories']);
                $adminActive = in_array($page, ['rapports', 'utilisateurs']);
                ?>

                <!-- Dropdown Ventes -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $ventesActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-shopping-basket"></i>Ventes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= $page === 'pos' ? 'active' : '' ?>" href="index.php?page=pos"><i class="fas fa-plus-circle"></i>Nouvelle vente</a></li>
                        <li><a class="dropdown-item <?= $page === 'history' ? 'active' : '' ?>" href="index.php?page=history"><i class="fas fa-history"></i>Historique</a></li>
                        <li><a class="dropdown-item <?= $page === 'credits' ? 'active' : '' ?>" href="index.php?page=credits"><i class="fas fa-hand-holding-usd"></i>Crédits</a></li>
                        <li><a class="dropdown-item <?= $page === 'clients' ? 'active' : '' ?>" href="index.php?page=clients"><i class="fas fa-users-viewfinder"></i>Gestion Clients</a></li>
                        <div class="dropdown-divider"></div>
                        <li><a class="dropdown-item <?= $page === 'caisse' ? 'active' : '' ?>" href="index.php?page=caisse"><i class="fas fa-cash-register"></i>Caisse</a></li>
                    </ul>
                </li>

                <!-- Dropdown Inventaire -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $inventaireActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-boxes"></i>Inventaire
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= $page === 'produits' ? 'active' : '' ?>" href="index.php?page=produits"><i class="fas fa-box"></i>Produits</a></li>
                        <?php if (isAdmin()): ?>
                        <li><a class="dropdown-item <?= $page === 'categories' ? 'active' : '' ?>" href="index.php?page=categories"><i class="fas fa-tags"></i>Catégories</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Dropdown Administration -->
                <?php if (isAdmin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $adminActive ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield"></i>Administration
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= $page === 'rapports' ? 'active' : '' ?>" href="index.php?page=rapports"><i class="fas fa-chart-bar"></i>Rapports</a></li>
                        <li><a class="dropdown-item <?= $page === 'utilisateurs' ? 'active' : '' ?>" href="index.php?page=utilisateurs"><i class="fas fa-users-cog"></i>Utilisateurs</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Standalone Nouvelle Vente Button -->
            <div class="d-none d-lg-flex me-3">
                <a href="index.php?page=pos" class="btn btn-navbar-primary">
                    <i class="fas fa-plus-circle"></i>Nouvelle vente
                </a>
            </div>

            <!-- Right: user + logout -->
            <div class="d-flex align-items-center gap-3">
                <div class="user-chip">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['user_login'] ?? 'U', 0, 2)) ?>
                    </div>
                    <span><?= sanitize($_SESSION['user_login'] ?? '') ?></span>
                    <span class="user-role-badge"><?= sanitize($_SESSION['user_role'] ?? '') ?></span>
                </div>
                <a href="index.php?page=logout" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i>Déconnexion
                </a>
            </div>

        </div>
    </div>
</nav>

<?php $flash = getFlash(); if ($flash): ?>
<div class="container-fluid px-4 mt-3">
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show flash-bar py-2 shadow-sm">
        <i class="fas fa-<?= $flash['type'] === 'error' ? 'exclamation-circle' : 'check-circle' ?> me-2"></i>
        <?= $flash['msg'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<div class="container-fluid px-4 py-4">