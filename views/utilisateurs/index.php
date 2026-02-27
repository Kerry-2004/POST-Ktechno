<?php defined('VIEW_PATH') || die(header('Location: ../../index.php'));
$pageTitle = 'Gestion des Utilisateurs'; require VIEW_PATH . '/partials/header.php'; ?>

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

    /* ════ CARD ════ */
    .pos-card {
        background: var(--surface);
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 4px 24px var(--brand-glow) !important;
        overflow: hidden;
    }
    .pos-card .card-header {
        background: var(--surface) !important;
        border-bottom: 1.5px solid var(--border) !important;
        padding: 15px 20px;
        display: flex; align-items: center;
        justify-content: space-between; gap: 10px;
    }
    .pos-card .card-header h6 { color: var(--text); font-size: 0.95rem; font-weight: 700; margin: 0; }
    .pos-card .card-header h6 i { color: var(--brand) !important; }

    /* ════ FORM ════ */
    .field-label {
        font-size: 0.75rem; font-weight: 700; color: var(--text);
        text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px; display: block;
    }
    .pos-input, .pos-select {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--text) !important;
        padding: 9px 13px; font-size: 0.875rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }
    .pos-input:focus, .pos-select:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important;
        outline: none;
    }
    .pos-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6897' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 13px center !important;
        padding-right: 36px !important;
    }

    /* ════ BUTTONS ════ */
    .btn-brand-solid {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 9px 20px;
        font-size: 0.875rem; transition: background 0.2s, transform 0.18s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px; text-decoration: none;
    }
    .btn-brand-solid:hover { background: var(--brand-dark) !important; color:#fff !important; transform: translateY(-1px); }

    /* ════ TABLE ════ */
    .users-table thead th {
        background: var(--brand-light) !important; color: var(--brand-dark) !important;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; border: none !important; padding: 11px 16px;
    }
    .users-table tbody td {
        padding: 13px 16px; border-color: var(--border) !important;
        font-size: 0.875rem; vertical-align: middle; color: var(--text);
    }
    .users-table tbody tr:hover td { background: var(--brand-light) !important; }

    .badge-id { background: var(--brand-light); color: var(--brand); font-weight: 700; font-size: 0.74rem; padding: 4px 9px; border-radius: 6px; }
    
    .role-badge {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        padding: 3px 10px; border-radius: 99px; letter-spacing: 0.4px;
    }
    .role-admin { background: #fee2e2; color: #dc2626; }
    .role-caissier { background: #dcfce7; color: #16a34a; }

    /* ════ ANIMATIONS ════ */
    .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.45s ease forwards; }
    .s1 { animation-delay: 0.05s; } .s2 { animation-delay: 0.15s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
</style>

<div class="row">
    <!-- Left: Add User Form -->
    <div class="col-lg-4 mb-4 fade-up s1">
        <div class="pos-card card border-0">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user-plus me-2"></i>Ajouter un utilisateur</h6>
            </div>
            <div class="card-body">
                <form action="index.php?page=utilisateurs&action=store" method="POST">
                    <div class="mb-3">
                        <label class="field-label">Nom d'utilisateur (Login)</label>
                        <input type="text" name="login" class="pos-input" placeholder="Ex: jean_dupont" required>
                    </div>
                    <div class="mb-3">
                        <label class="field-label">Mot de passe</label>
                        <input type="password" name="password" class="pos-input" placeholder="••••••••" required>
                    </div>
                    <div class="mb-4">
                        <label class="field-label">Rôle</label>
                        <select name="role" class="pos-select" required>
                            <option value="caissier">Caissier</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-brand-solid w-100 justify-content-center">
                        <i class="fas fa-save"></i>Créer l'utilisateur
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Users List -->
    <div class="col-lg-8 fade-up s2">
        <div class="pos-card card border-0">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Utilisateurs existants</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Login</th>
                                <th>Rôle</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><span class="badge-id"><?= $u['id'] ?></span></td>
                                <td class="fw-bold"><?= sanitize($u['login']) ?></td>
                                <td>
                                    <span class="role-badge role-<?= $u['role'] ?>">
                                        <?= $u['role'] === 'admin' ? 'Administrateur' : 'Caissier' ?>
                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:0.8rem;">
                                    <?= formatDate($u['created_at']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/partials/footer.php'; ?>
