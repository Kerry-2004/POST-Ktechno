<?php $pageTitle = 'Historique'; require VIEW_PATH . '/partials/header.php'; ?>

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

    /* ════ SHARED CARD ════ */
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

    /* ════ FILTER FORM ════ */
    .filter-card .card-body { padding: 18px 20px; }

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
    .pos-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6897' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important; background-repeat: no-repeat !important; background-position: right 13px center !important; padding-right: 36px !important; }

    .btn-filter {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 9px 20px;
        font-size: 0.875rem; transition: background 0.2s, transform 0.18s; cursor: pointer;
        white-space: nowrap;
    }
    .btn-filter:hover { background: var(--brand-dark) !important; transform: translateY(-1px); }

    .btn-reset {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 9px 13px; font-size: 0.875rem;
        transition: border-color 0.2s, color 0.2s; text-decoration: none;
        display: flex; align-items: center;
    }
    .btn-reset:hover { border-color: var(--danger) !important; color: var(--danger) !important; }

    /* ════ NEW SALE BUTTON ════ */
    .btn-new-sale {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 7px 16px;
        font-size: 0.82rem; box-shadow: 0 3px 12px var(--brand-glow);
        transition: background 0.2s, transform 0.18s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-new-sale:hover { background: var(--brand-dark) !important; color: #fff !important; transform: translateY(-1px); }

    /* ════ COUNT BADGE ════ */
    .count-badge {
        background: var(--brand-light); color: var(--brand); font-size: 0.72rem;
        font-weight: 800; padding: 3px 10px; border-radius: 99px; margin-left: 6px;
    }

    /* ════ TABLE ════ */
    .history-table thead th {
        background: var(--brand-light) !important; color: var(--brand-dark) !important;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; border: none !important; padding: 11px 16px;
    }
    .history-table tbody td {
        padding: 13px 16px; border-color: var(--border) !important;
        font-size: 0.875rem; vertical-align: middle; color: var(--text);
    }
    .history-table tbody tr:hover td { background: var(--brand-light) !important; }
    .table-row-annulee td { opacity: 0.5; }

    .badge-order-id {
        background: var(--brand-light); color: var(--brand);
        font-weight: 700; font-size: 0.74rem; padding: 4px 9px; border-radius: 6px;
    }
    .amount-pos  { color: var(--success); font-weight: 700; }
    .date-pos    { color: var(--text-muted); font-size: 0.81rem; }

    /* Row action buttons */
    .btn-xs { padding: 5px 10px; font-size: 0.74rem; border-radius: 7px; transition: all 0.15s; }
    .btn-xs.btn-outline-primary  { border-color: var(--brand)   !important; color: var(--brand)   !important; }
    .btn-xs.btn-outline-primary:hover  { background: var(--brand)   !important; color: #fff !important; transform: scale(1.08); }
    .btn-xs.btn-outline-warning  { border-color: var(--warning) !important; color: var(--warning) !important; }
    .btn-xs.btn-outline-warning:hover  { background: var(--warning) !important; color: #fff !important; transform: scale(1.08); }
    .btn-xs.btn-outline-danger   { border-color: #fca5a5 !important; color: var(--danger) !important; background: var(--danger-bg) !important; }
    .btn-xs.btn-outline-danger:hover   { background: var(--danger) !important; border-color: var(--danger) !important; color: #fff !important; transform: scale(1.08); }

    /* Empty state */
    .empty-state-icon { width: 56px; height: 56px; background: var(--brand-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
    .empty-state-icon i { color: var(--brand); font-size: 1.2rem; }

    /* ════ CANCEL MODAL ════ */
    .modal-content {
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 20px 60px rgba(69,61,222,0.18) !important;
        overflow: hidden;
    }
    .modal-header { background: var(--surface) !important; border-bottom: 1.5px solid var(--border) !important; padding: 16px 20px; }
    .modal-header .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .modal-body { padding: 24px 24px 16px; }
    .modal-footer { border-top: 1.5px solid var(--border) !important; padding: 14px 20px; }

    .warning-ring {
        width: 60px; height: 60px; background: var(--danger-bg); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;
        animation: ringPop 0.35s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes ringPop {
        from { transform: scale(0.4); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .warning-ring i { color: var(--danger); font-size: 1.4rem; }

    .btn-confirm-cancel {
        background: var(--danger) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 9px 20px;
        font-size: 0.875rem; cursor: pointer; transition: background 0.2s;
    }
    .btn-confirm-cancel:hover { background: #b91c1c !important; }

    .btn-modal-ghost {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 9px 20px; font-size: 0.875rem;
        cursor: pointer; transition: border-color 0.2s, color 0.2s;
    }
    .btn-modal-ghost:hover { border-color: var(--brand) !important; color: var(--brand) !important; }

    /* ════ ANIMATIONS ════ */
    .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.45s ease forwards; }
    .s1 { animation-delay: 0.05s; } .s2 { animation-delay: 0.15s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
</style>

<!-- Page header -->
<div class="d-flex align-items-center justify-content-between mb-4 fade-up s1">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);letter-spacing:-0.4px;margin:0;">
            Historique des <span style="color:var(--brand);">ventes</span>
        </h1>
        <p style="color:var(--text-muted);font-size:0.81rem;margin:3px 0 0;">
            <i class="fas fa-calendar me-1"></i><?= date('d/m/Y') ?>
        </p>
    </div>
</div>

<!-- Filter card -->
<div class="pos-card card border-0 filter-card mb-4 fade-up s1">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="history">
            <div class="col-md-3">
                <label class="field-label">Recherche (client / #)</label>
                <input type="text" name="search" class="pos-input" placeholder="Nom ou numéro de commande"
                       value="<?= sanitize($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="field-label">Date début</label>
                <input type="date" name="date_debut" class="pos-input"
                       value="<?= sanitize($_GET['date_debut'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="field-label">Date fin</label>
                <input type="date" name="date_fin" class="pos-input"
                       value="<?= sanitize($_GET['date_fin'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="field-label">Statut</label>
                <select name="status" class="pos-select">
                    <option value="">Tous les statuts</option>
                    <option value="validee"  <?= ($_GET['status']??'')==='validee'  ? 'selected':''?>>Validée</option>
                    <option value="en_cours" <?= ($_GET['status']??'')==='en_cours' ? 'selected':''?>>En cours</option>
                    <option value="annulee"  <?= ($_GET['status']??'')==='annulee'  ? 'selected':''?>>Annulée</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-filter flex-grow-1">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
                <a href="index.php?page=history" class="btn-reset" title="Réinitialiser">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders table -->
<div class="pos-card card border-0 fade-up s2">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-list me-2"></i>Commandes
            <span class="count-badge"><?= $total ?></span>
        </h6>
        <div class="d-flex gap-2">
            <?php
            $exportParams = http_build_query(array_filter([
                'page'       => 'rapports',
                'action'     => 'exportHistoriqueCsv',
                'date_debut' => $_GET['date_debut'] ?? '',
                'date_fin'   => $_GET['date_fin']   ?? '',
                'status'     => $_GET['status']     ?? '',
                'search'     => $_GET['search']     ?? '',
            ]));
            ?>
            <a href="index.php?<?= $exportParams ?>" style="background:var(--success-bg);border:1.5px solid var(--success);color:var(--success);border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:5px;">
                <i class="fas fa-file-csv"></i>CSV
            </a>
            <a href="index.php?page=pos" class="btn-new-sale">
                <i class="fas fa-plus"></i>Nouvelle vente
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle history-table">
                <thead>
                    <tr>
                        <th>#</th><th>Client</th><th>Caissier</th><th>Paiement</th>
                        <th>Total</th><th>Statut</th><th>Date</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($commandes)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                            <div style="font-weight:700;color:var(--text);font-size:0.9rem;">Aucune commande</div>
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px;">Essayez d'autres filtres ou créez une nouvelle vente.</div>
                        </td>
                    </tr>
                <?php else: foreach ($commandes as $cmd): ?>
                <?php
                $pmIcons = ['especes'=>'fas fa-money-bill-wave','credit'=>'fas fa-hand-holding-usd','mobile'=>'fas fa-mobile-alt'];
                $pmLabels = ['especes'=>'Espèces','credit'=>'Crédit','mobile'=>'Mobile'];
                $pm = $cmd['payment_method'] ?? 'especes';
                ?>
                <tr class="<?= $cmd['status'] === 'annulee' ? 'table-row-annulee' : '' ?>">
                    <td><span class="badge-order-id">#<?= $cmd['id'] ?></span></td>
                    <td class="fw-medium"><?= sanitize($cmd['client_name']) ?></td>
                    <td style="font-size:.8rem;color:var(--text-muted);"><?= sanitize($cmd['caissier'] ?? '—') ?></td>
                    <td style="font-size:.8rem;"><i class="<?= $pmIcons[$pm] ?? 'fas fa-coins' ?> me-1" style="color:var(--brand);"></i><?= $pmLabels[$pm] ?? ucfirst($pm) ?></td>
                    <td class="amount-pos"><?= formatPrice((float)$cmd['total_amount']) ?></td>
                    <td><?= statusBadge($cmd['status']) ?></td>
                    <td class="date-pos"><?= formatDate($cmd['created_at']) ?></td>
                    <td class="text-end">
                        <a href="index.php?page=commande&action=ticket&id=<?= $cmd['id'] ?>"
                           class="btn btn-xs btn-outline-primary" title="Ticket">
                            <i class="fas fa-print"></i>
                        </a>
                        <?php if ($cmd['status'] !== 'annulee'): ?>
                        <a href="index.php?page=commande&action=edit&id=<?= $cmd['id'] ?>"
                           class="btn btn-xs btn-outline-warning ms-1" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-xs btn-outline-danger ms-1" title="Annuler"
                                onclick="confirmerAnnulation(<?= $cmd['id'] ?>)">
                            <i class="fas fa-ban"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="card-footer" style="background:var(--surface);border-top:1.5px solid var(--border);padding:12px 20px;">
        <nav>
            <ul class="pagination pagination-sm mb-0 justify-content-center" style="gap:4px;">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" style="border-radius:7px;border-color:var(--border);<?= $i === $page ? 'background:var(--brand);border-color:var(--brand);color:#fff;' : 'color:var(--brand);' ?>"
                       href="index.php?page=history&p=<?= $i ?>&date_debut=<?= urlencode($_GET['date_debut']??'') ?>&date_fin=<?= urlencode($_GET['date_fin']??'') ?>&status=<?= urlencode($_GET['status']??'') ?>&search=<?= urlencode($_GET['search']??'') ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <div class="text-center mt-2" style="font-size:.75rem;color:var(--text-muted);">
            Page <?= $page ?> / <?= $pages ?> — <?= $total ?> commande(s)
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Cancel confirmation modal -->
<div class="modal fade" id="modalAnnuler" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title"><i class="fas fa-ban me-2 text-danger"></i>Annuler la commande</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="warning-ring"><i class="fas fa-exclamation"></i></div>
                <div style="font-weight:700;color:var(--text);font-size:0.95rem;margin-bottom:6px;">
                    Commande <span id="modalCmdNum" style="color:var(--brand);"></span>
                </div>
                <p style="color:var(--text-muted);font-size:0.84rem;margin:0;">
                    Cette action est irréversible. Confirmer l'annulation ?
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-2 border-0">
                <button class="btn-modal-ghost" data-bs-dismiss="modal">Annuler</button>
                <button class="btn-confirm-cancel" id="btnConfirmCancel">
                    <i class="fas fa-ban me-1"></i>Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<?php $extraJs = <<<'JS'
let cancelTargetId = null;
const cancelModal  = new bootstrap.Modal(document.getElementById('modalAnnuler'));

function confirmerAnnulation(id) {
    cancelTargetId = id;
    document.getElementById('modalCmdNum').textContent = '#' + id;
    cancelModal.show();
}

document.getElementById('btnConfirmCancel').addEventListener('click', async function() {
    if (!cancelTargetId) return;
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>En cours…';
    try {
        const res  = await fetch('index.php?page=commande&action=cancel&id=' + cancelTargetId);
        const data = await res.json();
        if (data.success) {
            cancelModal.hide();
            location.reload();
        } else {
            alert('Erreur : ' + data.message);
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-ban me-1"></i>Confirmer';
        }
    } catch(e) {
        alert('Erreur réseau.');
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-ban me-1"></i>Confirmer';
    }
});
JS;
?>
<?php require VIEW_PATH . '/partials/footer.php'; ?>