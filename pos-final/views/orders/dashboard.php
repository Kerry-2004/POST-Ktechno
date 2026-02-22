<?php $pageTitle = 'Tableau de bord'; require VIEW_PATH . '/partials/header.php'; ?>

<style>
    :root {
        --brand:        #453dde;
        --brand-dark:   #2d27a8;
        --brand-light:  #eeecfd;
        --brand-mid:    #c7c3f7;
        --brand-glow:   rgba(69, 61, 222, 0.13);

        --success:      #16a34a;
        --success-bg:   #dcfce7;
        --danger:       #dc2626;
        --danger-bg:    #fee2e2;
        --warning:      #d97706;
        --warning-bg:   #fef3c7;

        --surface:      #ffffff;
        --bg:           #f5f4ff;
        --border:       #e8e6fb;
        --text:         #1a1740;
        --text-muted:   #6b6897;

        --radius:       16px;
        --radius-sm:    10px;
    }

    body { background: var(--bg) !important; }

    /* ════ STAT CARDS ════ */
    .stat-card {
        background: var(--surface) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 2px 12px var(--brand-glow) !important;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 36px var(--brand-glow) !important;
    }
    .stat-accent-danger  { border-top: 3px solid var(--danger)  !important; }
    .stat-accent-success { border-top: 3px solid var(--success) !important; }
    .stat-accent-brand   { border-top: 3px solid var(--brand)   !important; }
    .stat-accent-warning { border-top: 3px solid var(--warning) !important; }

    .stat-icon {
        width: 54px; height: 54px;
        border-radius: var(--radius-sm) !important;
        display: flex !important;
        align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.bg-danger   { background: var(--danger-bg)   !important; }
    .stat-icon.bg-success  { background: var(--success-bg)  !important; }
    .stat-icon.bg-primary  { background: var(--brand-light) !important; }
    .stat-icon.bg-warning  { background: var(--warning-bg)  !important; }
    .stat-icon.text-danger  { color: var(--danger)  !important; }
    .stat-icon.text-success { color: var(--success) !important; }
    .stat-icon.text-primary { color: var(--brand)   !important; }
    .stat-icon.text-warning { color: var(--warning) !important; }

    .stat-value {
        font-size: 1.6rem; font-weight: 800;
        color: var(--text); line-height: 1.1; letter-spacing: -0.6px;
    }
    .stat-label {
        font-size: 0.76rem; color: var(--text-muted); font-weight: 500;
        margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* ════ PAGE HEADER ════ */
    .dash-page-title { font-size: 1.45rem; font-weight: 800; color: var(--text); letter-spacing: -0.5px; margin: 0; }
    .dash-page-title span { color: var(--brand); }
    .dash-status-dot {
        display: inline-block; width: 8px; height: 8px;
        background: var(--success); border-radius: 50%; margin-right: 6px;
        animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.6; transform: scale(1.3); }
    }

    /* ════ BUTTONS ════ */
    .btn-brand-solid {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 11px 26px;
        font-size: 0.9rem; box-shadow: 0 4px 16px var(--brand-glow);
        transition: background 0.2s, transform 0.18s, box-shadow 0.18s;
    }
    .btn-brand-solid:hover { background: var(--brand-dark) !important; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(69,61,222,0.28); }
    .btn-brand-ghost {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 11px 26px; font-size: 0.9rem;
        transition: border-color 0.2s, color 0.2s, transform 0.18s;
    }
    .btn-brand-ghost:hover { border-color: var(--brand) !important; color: var(--brand) !important; transform: translateY(-2px); }

    /* ════ ORDERS CARD ════ */
    .orders-card {
        background: var(--surface); border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important; box-shadow: 0 4px 24px var(--brand-glow) !important; overflow: hidden;
    }
    .orders-card .card-header { background: var(--surface) !important; border-bottom: 1.5px solid var(--border) !important; padding: 15px 20px; }
    .orders-card .card-header h6 { color: var(--text); font-size: 0.95rem; font-weight: 700; }
    .orders-card .card-header h6 i { color: var(--brand) !important; }

    .orders-card thead th {
        background: var(--brand-light) !important; color: var(--brand-dark) !important;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; border: none !important; padding: 11px 16px;
    }
    .orders-card tbody td { padding: 13px 16px; border-color: var(--border) !important; font-size: 0.875rem; vertical-align: middle; color: var(--text); }
    .orders-card tbody tr:hover td { background: var(--brand-light) !important; }
    .table-row-annulee td { opacity: 0.5; }

    .badge-order-id { background: var(--brand-light); color: var(--brand); font-weight: 700; font-size: 0.74rem; padding: 4px 9px; border-radius: 6px; }
    .amount-pos  { color: var(--success); font-weight: 700; }
    .date-pos    { color: var(--text-muted); font-size: 0.81rem; }

    .btn-xs { padding: 5px 10px; font-size: 0.74rem; border-radius: 7px; transition: all 0.15s; }
    .btn-xs.btn-outline-primary  { border-color: var(--brand)   !important; color: var(--brand)   !important; }
    .btn-xs.btn-outline-primary:hover  { background: var(--brand)   !important; color: #fff !important; transform: scale(1.08); }
    .btn-xs.btn-outline-warning  { border-color: var(--warning) !important; color: var(--warning) !important; }
    .btn-xs.btn-outline-warning:hover  { background: var(--warning) !important; color: #fff !important; transform: scale(1.08); }

    .btn-see-all { font-size: 0.78rem; font-weight: 600; color: var(--brand) !important; background: var(--brand-light) !important; border: none !important; border-radius: 7px; padding: 5px 13px; transition: background 0.2s; }
    .btn-see-all:hover { background: var(--brand-mid) !important; }

    .empty-state-icon { width: 56px; height: 56px; background: var(--brand-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
    .empty-state-icon i { color: var(--brand); font-size: 1.2rem; }

    /* ════ ANIMATIONS ════ */
    .fade-up { opacity: 0; transform: translateY(18px); animation: fadeUp 0.5s ease forwards; }
    .s1 { animation-delay: 0.05s; } .s2 { animation-delay: 0.13s; }
    .s3 { animation-delay: 0.21s; } .s4 { animation-delay: 0.29s; }
    .s5 { animation-delay: 0.37s; } .s6 { animation-delay: 0.42s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4 fade-up s1">
    <div>
        <h1 class="dash-page-title">Tableau de <span>bord</span></h1>
        <p style="color:var(--text-muted);font-size:0.82rem;margin:4px 0 0;">
            <span class="dash-status-dot"></span>Système actif &nbsp;·&nbsp; <?= date('d/m/Y') ?>
        </p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6 fade-up s1">
        <div class="stat-card stat-accent-danger card h-100 border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger rounded-3">
                    <i class="fas fa-shopping-bag fa-lg"></i>
                </div>
                <div>
                    <div class="stat-value"><?= (int)$stats['today']['nb'] ?></div>
                    <div class="stat-label">Ventes aujourd'hui</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 fade-up s2">
        <div class="stat-card stat-accent-success card h-100 border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success rounded-3">
                    <i class="fas fa-dollar-sign fa-lg"></i>
                </div>
                <div>
                    <div class="stat-value"><?= formatPrice((float)$stats['today']['total']) ?></div>
                    <div class="stat-label">CA aujourd'hui</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 fade-up s3">
        <div class="stat-card stat-accent-brand card h-100 border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </div>
                <div>
                    <div class="stat-value"><?= (int)$stats['month']['nb'] ?></div>
                    <div class="stat-label">Ventes ce mois</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 fade-up s4">
        <div class="stat-card stat-accent-warning card h-100 border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <div>
                    <div class="stat-value"><?= formatPrice((float)$stats['month']['total']) ?></div>
                    <div class="stat-label">CA ce mois</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-4 fade-up s5">
    <a href="index.php?page=pos" class="btn btn-brand-solid me-2">
        <i class="fas fa-plus me-2"></i>Nouvelle vente
    </a>
    <a href="index.php?page=history" class="btn btn-brand-ghost">
        <i class="fas fa-list me-2"></i>Historique
    </a>
</div>

<!-- Recent Orders -->
<div class="orders-card card border-0 fade-up s6">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-clock me-2"></i>Dernières commandes
        </h6>
        <a href="index.php?page=history" class="btn btn-see-all">
            <i class="fas fa-arrow-right me-1"></i>Tout voir
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>#</th><th>Client</th><th>Total</th>
                        <th>Statut</th><th>Date</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($commandes)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                            <div style="font-weight:700;color:var(--text);font-size:0.9rem;">Aucune commande</div>
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px;">Les ventes apparaîtront ici.</div>
                        </td>
                    </tr>
                <?php else: foreach (array_slice($commandes, 0, 10) as $cmd): ?>
                <tr class="<?= $cmd['status'] === 'annulee' ? 'table-row-annulee' : '' ?>">
                    <td><span class="badge-order-id">#<?= $cmd['id'] ?></span></td>
                    <td class="fw-medium"><?= sanitize($cmd['client_name']) ?></td>
                    <td class="amount-pos"><?= formatPrice((float)$cmd['total_amount']) ?></td>
                    <td><?= statusBadge($cmd['status']) ?></td>
                    <td class="date-pos"><?= formatDate($cmd['created_at']) ?></td>
                    <td class="text-end">
                        <a href="index.php?page=commande&action=ticket&id=<?= $cmd['id'] ?>"
                           class="btn btn-xs btn-outline-primary" title="Ticket"><i class="fas fa-print"></i></a>
                        <?php if ($cmd['status'] !== 'annulee'): ?>
                        <a href="index.php?page=commande&action=edit&id=<?= $cmd['id'] ?>"
                           class="btn btn-xs btn-outline-warning ms-1" title="Modifier"><i class="fas fa-edit"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/partials/footer.php'; ?>