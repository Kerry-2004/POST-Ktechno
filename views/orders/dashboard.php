<?php $pageTitle = 'Tableau de bord'; require VIEW_PATH . '/partials/header.php'; ?>
<style>
    :root {
        --brand: #453dde;
        --brand-light: #eeecfd;
        --brand-mid: #c7c3f7;
        --brand-glow: rgba(69, 61, 222, 0.08);
        --success: #22c55e;
        --success-bg: #f0fdf4;
        --danger: #ef4444;
        --danger-bg: #fef2f2;
        --warning: #f59e0b;
        --warning-bg: #fffbeb;
        --surface: #ffffff;
        --bg: #f8faff;
        --border: #edf2f7;
        --text: #1a202c;
        --text-muted: #718096;
        --radius: 20px;
        --radius-sm: 12px;
    }

    body { background: var(--bg) !important; font-family: 'Inter', sans-serif; }

    .dash-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        padding: 24px;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .dash-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04); }

    .stat-mini-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 600; margin-bottom: 8px; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--text); letter-spacing: -1px; }
    .stat-badge {
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-down { background: var(--danger-bg); color: var(--danger); }
    .badge-up { background: var(--success-bg); color: var(--success); }

    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }

    .section-title { font-size: 1.15rem; font-weight: 800; color: var(--text); margin-bottom: 20px; }

    .chart-container { position: relative; width: 100%; }

    .rank-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); }
    .rank-row:last-child { border-bottom: none; }
    .rank-num { width: 24px; height: 24px; border-radius: 50%; background: var(--brand-light); color: var(--brand); font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; justify-content: center; }

    /* Custom Table Style */
    .pos-table thead th {
        background: transparent !important;
        border-bottom: 1px solid var(--border) !important;
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 16px;
    }
    .pos-table tbody td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 0.875rem; color: var(--text); }
    .pos-table tbody tr:last-child td { border-bottom: none; }

    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; }

    /* Animation */
    .fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    .s1 { animation-delay: 0.1s; } .s2 { animation-delay: 0.2s; } .s3 { animation-delay: 0.3s; }

    /* Fix for contrast in light mode (overriding app.css dark theme) */
    .btn-outline-secondary {
        color: var(--text-muted) !important;
        border-color: var(--border) !important;
        background-color: transparent !important;
    }
    .btn-outline-secondary:hover {
        background-color: var(--brand-light) !important;
        color: var(--brand) !important;
        border-color: var(--brand-mid) !important;
    }
    .text-muted { color: var(--text-muted) !important; }
    .link-muted { color: var(--text-muted) !important; }
    .link-dark { color: var(--text) !important; }
    .dropdown-item.active, .dropdown-item:active { background-color: var(--brand); }
</style>

<!-- Session caisse banner -->
<?php if (isset($sessionActive) && $sessionActive): ?>
<div class="caisse-banner fade-up s1" style="background:linear-gradient(135deg,var(--brand),#2d27a8); color:#fff; border-radius:var(--radius); padding:16px 24px; margin-bottom:24px; display:flex; align-items:center; gap:16px;">
    <i class="fas fa-cash-register fa-lg"></i>
    <div>
        <div style="font-weight:700;font-size:0.95rem;">Session caisse ouverte par <?= sanitize($sessionActive['caissier'] ?? 'N/A') ?></div>
        <div style="font-size:0.8rem;opacity:0.9;">Solde ouverture : <?= formatPrice((float)$sessionActive['solde_ouverture']) ?> · depuis <?= formatDate($sessionActive['ouvert_a']) ?></div>
    </div>
    <a href="index.php?page=caisse" class="ms-auto btn btn-sm btn-light" style="border-radius:8px; font-weight:700; color:var(--brand);">Gérer</a>
</div>
<?php endif; ?>

<div class="row mb-4 align-items-end fade-up s1">
    <div class="col">
        <h1 style="font-size:1.75rem; font-weight:900; color:var(--text); letter-spacing:-1px; margin:0;">Tableau de bord</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin:4px 0 0;">Vue d'ensemble des ventes, des produits et des performances.</p>
    </div>
    <div class="col-auto">
        <div class="d-flex gap-2">
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0 shadow-none"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 shadow-none" placeholder="Rechercher...">
            </div>
            <a href="index.php?page=pos" class="btn btn-navbar-primary px-4" style="height:42px;">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle vente
            </a>
            <button class="btn btn-outline-secondary border shadow-none" style="border-radius:10px; font-weight:600;"><i class="fas fa-file-export me-2"></i>Export CSV</button>
            <button class="btn btn-outline-secondary border shadow-none" style="border-radius:10px; font-weight:600;"><i class="fas fa-download me-2"></i>Rapport complet</button>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Key Stats -->
    <div class="col-lg-3 fade-up s2">
    <div class="dash-card mb-4" style="background:#fff;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="stat-label">Ventes du mois</div>
            <div class="icon-box"><i class="fas fa-shopping-basket"></i></div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="stat-value"><?= number_format((float)($stats['month']['nb'] ?? 0)) ?></div>
            <div class="stat-badge badge-up">+12% <i class="fas fa-arrow-up"></i></div>
        </div>
        <a href="index.php?page=history" class="text-decoration-none mt-4 d-flex justify-content-between align-items-center" style="font-size:0.85rem; color:var(--brand); font-weight:700;">
            Détails des ventes <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <div class="dash-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="stat-label">Volume de produits</div>
            <div class="icon-box"><i class="fas fa-box"></i></div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="stat-value"><?= number_format((float)($totalProduits ?? 0)) ?></div>
            <div class="stat-badge badge-up">+5% <i class="fas fa-arrow-up"></i></div>
        </div>
        <a href="index.php?page=produits" class="text-decoration-none mt-4 d-flex justify-content-between align-items-center" style="font-size:0.85rem; color:var(--brand); font-weight:700;">
            Gérer l'inventaire <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

    <!-- Center: Profit Chart -->
    <div class="col-lg-5 fade-up s2">
    <div class="dash-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title mb-0">Vue d'ensemble des revenus</div>
            <div class="dropdown">
                <button class="btn btn-link link-dark text-muted p-0" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </div>
        <div class="d-flex align-items-center gap-4 mb-2">
            <div style="font-size: 2rem; font-weight: 800; letter-spacing: -1.5px;"><?= formatPrice((float)($stats['month']['total'] ?? 0)) ?></div>
            <div class="stat-badge badge-up" style="font-size: 0.85rem; padding: 6px 14px;">+18% <i class="fas fa-arrow-up"></i></div>
        </div>
        <div class="d-flex gap-3 mb-4" style="font-size:0.75rem; font-weight:600; color:var(--text-muted);">
            <span class="d-flex align-items-center gap-2"><div style="width:8px; height:8px; border-radius:50%; background:var(--brand-mid);"></div> Chiffre d'affaires</span>
            <span class="d-flex align-items-center gap-2"><div style="width:8px; height:8px; border-radius:50%; background:var(--brand);"></div> Bénéfice estimé</span>
        </div>
        <div class="chart-container" style="height: 250px;">
            <canvas id="profitChart"></canvas>
        </div>
    </div>
</div>

    <!-- Right: Sales Stats Donut -->
    <div class="col-lg-4 fade-up s2">
    <div class="dash-card">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="section-title mb-0">Répartition des ventes</div>
            <select class="form-select border-0 bg-transparent text-muted fw-bold" style="width: auto; font-size: 0.85rem;">
                <option>Mensuel</option>
                <option>Hebdomadaire</option>
            </select>
        </div>
        
        <div class="position-relative d-flex justify-content-center align-items-center" style="height: 220px;">
            <canvas id="salesStatsChart" style="max-height: 200px;"></canvas>
            <div class="position-absolute text-center">
                <div style="font-size: 1.5rem; font-weight: 800;"><?= formatPrice((float)($stats['month']['total'] ?? 0)) ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Ventes du mois</div>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-12"><div style="font-size:0.85rem; color:var(--text-muted); font-weight:600;">Nombre total de ventes: <span class="text-dark fw-bold"><?= number_format((float)($stats['month']['nb'] ?? 0)) ?></span></div></div>
            <?php foreach (array_slice($caParCategorie, 0, 4) as $index => $cat): ?>
            <div class="col-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div style="width:8px; height:8px; border-radius:50%; background:<?= ['#453dde','#fb923c','#22c55e','#ef4444'][$index] ?? 'var(--brand)' ?>;"></div>
                    <div style="font-size:0.8rem; color:var(--text-muted); font-weight:600;"><?= sanitize($cat['categorie'] ?? 'Autres') ?></div>
                </div>
                <div style="font-size:0.95rem; font-weight:700;"><?= formatPrice((float)($cat['ca'] ?? 0)) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>

<div class="row g-4">
    <!-- Bottom Left: Recent Orders -->
    <div class="col-lg-8 fade-up s3">
        <div class="dash-card p-0 overflow-hidden">
            <div class="px-4 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <div class="section-title mb-1">Commandes récentes</div>
                    <p style="font-size:0.85rem; color:var(--text-muted); margin:0;">Suivi en temps réel des dernières transactions.</p>
                </div>
                <a href="index.php?page=history" class="btn btn-outline-secondary btn-sm border" style="border-radius:8px; font-weight:600; padding:8px 16px;">Tout voir <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table pos-table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Paiement</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($commandes, 0, 5) as $cmd): ?>
                        <tr>
                            <td><span class="text-muted fw-bold">#<?= $cmd['id'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box" style="width:32px; height:32px; border-radius:8px;"><i class="fas fa-box" style="font-size:0.8rem;"></i></div>
                                    <div class="fw-bold" style="font-size:0.85rem;"><?= sanitize($cmd['client_name']) ?> <span class="text-muted fw-normal">...</span></div>
                                </div>
                            </td>
                            <td class="text-muted"><?= date('d M Y', strtotime($cmd['created_at'])) ?></td>
                            <td class="text-muted text-capitalize"><?= sanitize($cmd['payment_method']) ?></td>
                            <td class="fw-bold"><?= formatPrice((float)$cmd['total_amount']) ?></td>
                            <td>
                                <?php if ($cmd['status'] === 'validee'): ?>
                                    <span class="stat-badge badge-up" style="padding:4px 12px;">Validée</span>
                                <?php elseif ($cmd['status'] === 'annulee'): ?>
                                    <span class="stat-badge badge-down" style="padding:4px 12px;">Annulée</span>
                                <?php else: ?>
                                    <span class="stat-badge bg-light text-muted" style="padding:4px 12px;">En cours</span>
                                <?php endif; ?>
                            </td>
                            <td><button class="btn btn-link link-muted p-0"><i class="fas fa-ellipsis-h"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Right: Top Products -->
    <div class="col-lg-4 fade-up s3">
        <div class="dash-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="section-title mb-0">Produits les plus vendus</div>
                <select class="form-select border-0 bg-transparent text-muted fw-bold" style="width: auto; font-size: 0.85rem;">
                    <option>Hebdomadaire</option>
                    <option>Mensuel</option>
                </select>
            </div>
            <div class="mb-4">
                <?php if (!empty($topProduits)): $first = $topProduits[0]; ?>
                <div class="d-flex border rounded-4 p-3 gap-3 mb-4">
                    <div class="icon-box" style="width:60px; height:60px; border-radius:12px; background:var(--brand-light); color:var(--brand);">
                        <i class="fas fa-shopping-bag fa-lg"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:0.95rem;"><?= sanitize($first['produit_nom'] ?? 'Produit') ?></div>
                        <div class="text-muted" style="font-size:0.8rem;"><?= number_format((float)($first['total_qte'] ?? 0)) ?> vendus</div>
                        <div class="fw-bold mt-1"><?= formatPrice($first['total_qte'] > 0 ? (float)$first['total_ca'] / $first['total_qte'] : 0.0) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="chart-container" style="height: 120px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
            
            <div class="mt-4">
                <?php foreach (array_slice($topProduits, 1, 4) as $index => $tp): ?>
                <div class="rank-row">
                    <div class="rank-num"><?= $index + 2 ?></div>
                    <div class="fw-bold" style="font-size:0.85rem; flex:1;"><?= sanitize($tp['produit_nom'] ?? 'Produit') ?></div>
                    <div style="font-size:0.85rem; color:var(--text-muted); font-weight:700;"><?= (int)($tp['total_qte'] ?? 0) ?> vendus</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    // Profit Overview Bar Chart
    const labelsCa = <?= json_encode(array_column($caParJour, 'jour')) ?>;
    const dataCa   = <?= json_encode(array_map(fn($r) => (float)$r['ca'], $caParJour)) ?>;
    
    new Chart(document.getElementById('profitChart'), {
        type: 'bar',
        data: {
            labels: labelsCa.map(l => new Date(l).toLocaleString('default', {month:'short'})),
            datasets: [{
                label: 'Bénéfice',
                data: dataCa,
                backgroundColor: '#fb923c',
                borderRadius: 4,
                barThickness: 12
            }, {
                label: 'Chiffre d\'affaires',
                data: dataCa.map(d => d * 0.8),
                backgroundColor: '#fed7aa',
                borderRadius: 4,
                barThickness: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Sales Stats Donut Chart
    const catLabels = <?= json_encode(array_column(array_slice($caParCategorie, 0, 4), 'categorie')) ?>;
    const catData   = <?= json_encode(array_map(fn($r) => (float)$r['ca'], array_slice($caParCategorie, 0, 4))) ?>;

    new Chart(document.getElementById('salesStatsChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: ['#453dde', '#fb923c', '#22c55e', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Top Products Wave Chart
    new Chart(document.getElementById('topProductsChart'), {
        type: 'line',
        data: {
            labels: labelsCa,
            datasets: [{
                data: dataCa,
                borderColor: '#fb923c',
                backgroundColor: 'rgba(251, 146, 60, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { display: false } }
        }
    });
</script>

<?php require VIEW_PATH . '/partials/footer.php'; ?>