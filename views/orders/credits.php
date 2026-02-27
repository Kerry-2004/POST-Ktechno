<?php $pageTitle = 'Suivi des crédits'; require VIEW_PATH . '/partials/header.php'; ?>

<style>
    :root {
        --brand: #453dde;
        --brand-dark: #2d27a8;
        --brand-light: #eeecfd;
        --brand-glow: rgba(69,61,222,0.13);
        --success: #16a34a;
        --success-bg: #dcfce7;
        --danger: #dc2626;
        --danger-bg: #fee2e2;
        --warning: #d97706;
        --warning-bg: #fef3c7;
        --surface: #fff;
        --bg: #f5f4ff;
        --border: #e8e6fb;
        --text: #1a1740;
        --text-2: #ffffffff;
        --text-muted: #6b6897;
        --radius: 16px;
        --radius-sm: 10px;
    }

    body { background: var(--bg) !important; }

    .header-bar {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px var(--brand-glow);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-card {
        background: var(--surface);
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 4px 24px var(--brand-glow) !important;
        overflow: hidden;
    }

    .pos-table th {
        background: var(--surface) !important;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1.5px solid var(--border) !important;
    }

    .pos-table td {
        padding: 14px 16px;
        vertical-align: middle;
        color: var(--text);
        font-size: 0.88rem;
        border-bottom: 1px solid var(--border) !important;
    }

    .table-row-credit:hover {
        background: var(--brand-light);
        cursor: pointer;
    }

    .badge-status {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-restant { background: var(--warning-bg); color: var(--warning); }
    .badge-paye { background: var(--success-bg); color: var(--success); }

    .modal-content {
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
    }

    .modal-header { color: var(--text-2); }

    .versement-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }
    .versement-item:last-child { border-bottom: none; }

    .btn-brand {
        background: var(--brand) !important;
        border: none !important;
        color: #fff !important;
        border-radius: var(--radius-sm) !important;
        font-weight: 700;
    }
    .btn-brand:hover { background: var(--brand-dark) !important; color: #fff !important; }

    .form-control-pos {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--text) !important;
        padding: 10px 14px;
    }
    .form-control-pos:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important;
    }
</style>

<div class="header-bar fade-up">
    <div>
        <h4 style="margin:0;font-weight:800;color:var(--text);font-size:1.2rem;">Suivi des crédits</h4>
        <div style="font-size:0.85rem;color:var(--text-muted);">Gérez les versements de vos clients</div>
    </div>
</div>

<div class="list-card fade-up s1">
    <div class="table-responsive">
        <table class="table pos-table mb-0 table-hover">
            <thead>
                <tr>
                    <th>Cmd #</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Montant Total</th>
                    <th>Déjà Versé</th>
                    <th>Reste à Payer</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($credits)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">Aucun crédit en cours.</td>
                </tr>
                <?php else: foreach ($credits as $c): 
                    $total = (float)($c['total_amount'] ?? 0);
                    $paye  = (float)($c['amount_paid'] ?? 0);
                    $reste = max(0, $total - $paye);
                    $dataJson = htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8');
                ?>
                <tr class="table-row-credit" data-credit="<?= $dataJson ?>" onclick="openCredit(this)">
                    <td style="font-weight:700;color:var(--brand);">#<?= $c['id'] ?></td>
                    <td style="font-weight:700;"><?= htmlspecialchars($c['client_name']) ?></td>
                    <td style="font-size:0.8rem;color:var(--text-muted);"><?= date('d/m/y H:i', strtotime($c['created_at'])) ?></td>
                    <td style="font-weight:800;"><?= formatPrice($total) ?></td>
                    <td style="color:var(--success);font-weight:700;"><?= formatPrice($paye) ?></td>
                    <td>
                        <?php if ($reste > 0): ?>
                            <span class="badge-status badge-restant"><?= formatPrice($reste) ?></span>
                        <?php else: ?>
                            <span class="badge-status badge-paye"><i class="fas fa-check me-1"></i>Soldé</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light" style="border-radius:8px;font-size:0.75rem;font-weight:700;">
                            Voir détails
                        </button>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Détails / Versements -->
<div class="modal fade" id="creditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title" style="font-weight:800;font-size:1.1rem;color:var(--text-2);">
                    Détails Crédit <span id="mCmdId" style="color:var(--brand);"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);margin-bottom:20px;">
                    <div style="font-weight:700;font-size:1rem;color:var(--text);margin-bottom:4px;" id="mClient"></div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Total</div>
                            <div style="font-weight:800;color:var(--text);font-size:0.9rem;" id="mTotal"></div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Versé</div>
                            <div style="font-weight:800;color:var(--success);font-size:0.9rem;" id="mPaye"></div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;">Reste</div>
                            <div style="font-weight:900;color:var(--danger);font-size:1.1rem;" id="mReste"></div>
                        </div>
                    </div>
                </div>

                <div id="historiqueSection" class="mb-4">
                    <h6 style="font-weight:700;font-size:0.85rem;color:var(--text);margin-bottom:12px;">Historique des versements</h6>
                    <div id="versementsList" style="max-height:180px;overflow-y:auto;padding-right:8px;">
                        <!-- JS injected -->
                    </div>
                </div>

                <div id="addVersementForm" style="display:none;border-top:1.5px solid var(--border);padding-top:16px;">
                    <h6 style="font-weight:700;font-size:0.85rem;color:var(--text);margin-bottom:12px;">Ajouter un paiement</h6>
                    <input type="hidden" id="vCmdId">
                    <div class="input-group">
                        <input type="number" id="vMontant" class="form-control form-control-pos" placeholder="Montant..." step="0.01" min="0.01">
                        <span class="input-group-text" style="background:var(--bg);border:1.5px solid var(--border);border-left:none;color:var(--text-muted);font-weight:700;">HTG</span>
                    </div>
                    <div id="vError" class="text-danger mt-2 d-none" style="font-size:0.78rem;font-weight:600;"></div>
                    <button class="btn btn-brand w-100 mt-3" onclick="saveVersement()">
                        <i class="fas fa-save me-2"></i>Enregistrer le versement
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$extraJs = <<<'JS'
let currentCredit = null;
const modal = new bootstrap.Modal(document.getElementById('creditModal'));

function formatMoney(val) {
    return Number(val).toLocaleString('fr-HT', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' HTG';
}

function openCredit(element) {
    let jsonStr = element.getAttribute('data-credit');
    currentCredit = JSON.parse(jsonStr);
    
    document.getElementById('mCmdId').textContent = '#' + currentCredit.id;
    document.getElementById('mClient').textContent = currentCredit.client_name;
    
    let total = parseFloat(currentCredit.total_amount) || 0;
    let paye  = parseFloat(currentCredit.amount_paid) || 0;
    let reste = Math.max(0, total - paye);
    
    document.getElementById('mTotal').textContent = formatMoney(total);
    document.getElementById('mPaye').textContent = formatMoney(paye);
    document.getElementById('mReste').textContent = formatMoney(reste);
    
    // Historique
    let html = '';
    if (currentCredit.versements && currentCredit.versements.length > 0) {
        currentCredit.versements.forEach(v => {
            let d = new Date(v.date_versement).toLocaleString('fr-FR', {day:'2-digit',month:'2-digit',year:'2-digit',hour:'2-digit',minute:'2-digit'});
            let user = v.caissier ? v.caissier : 'Système';
            html += `
            <div class="versement-item">
                <div>
                    <div style="font-size:0.8rem;font-weight:700;color:var(--text);">${formatMoney(v.montant)}</div>
                    <div style="font-size:0.7rem;color:var(--text-muted);"><i class="fas fa-user ms-1"></i> ${user}</div>
                </div>
                <div style="font-size:0.75rem;color:var(--text-muted);">${d}</div>
            </div>`;
        });
    } else {
        html = '<div class="text-muted" style="font-size:0.8rem;text-align:center;padding:10px 0;">Aucun versement enregistré.</div>';
    }
    document.getElementById('versementsList').innerHTML = html;
    
    // Formulaire d'ajout
    let formInfo = document.getElementById('addVersementForm');
    if (reste > 0) {
        formInfo.style.display = 'block';
        document.getElementById('vCmdId').value = currentCredit.id;
        document.getElementById('vMontant').value = reste; // Par défaut, on propose de solder
        document.getElementById('vMontant').max = reste;
        document.getElementById('vError').classList.add('d-none');
    } else {
        formInfo.style.display = 'none';
    }
    
    modal.show();
}

function saveVersement() {
    let id = document.getElementById('vCmdId').value;
    let mt = parseFloat(document.getElementById('vMontant').value);
    let err = document.getElementById('vError');
    err.classList.add('d-none');

    if (!id || !mt || mt <= 0) {
        err.textContent = 'Veuillez saisir un montant valide.';
        err.classList.remove('d-none');
        return;
    }

    let total = parseFloat(currentCredit.total_amount) || 0;
    let paye  = parseFloat(currentCredit.amount_paid) || 0;
    let reste = Math.max(0, total - paye);

    if (mt > reste) {
        err.textContent = 'Le versement ne peut pas dépasser le reste à payer (' + formatMoney(reste) + ').';
        err.classList.remove('d-none');
        return;
    }

    fetch('index.php?page=commande&action=versement', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ commande_id: id, montant: mt })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            err.textContent = data.message || 'Erreur lors de l\'enregistrement.';
            err.classList.remove('d-none');
        }
    })
    .catch(() => {
        err.textContent = 'Erreur réseau.';
        err.classList.remove('d-none');
    });
}
JS;

require VIEW_PATH . '/partials/footer.php';
?>
