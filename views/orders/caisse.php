<?php $pageTitle = 'Caisse — Ouverture / Fermeture'; require VIEW_PATH . '/partials/header.php'; ?>
<style>
    :root{--brand:#453dde;--brand-dark:#2d27a8;--brand-light:#eeecfd;--brand-glow:rgba(69,61,222,.13);--success:#16a34a;--success-bg:#dcfce7;--danger:#dc2626;--danger-bg:#fee2e2;--warning:#d97706;--warning-bg:#fef3c7;--surface:#fff;--bg:#f5f4ff;--border:#e8e6fb;--text:#1a1740;--text-muted:#6b6897;--radius:16px;--radius-sm:10px;}
    body{background:var(--bg)!important;}
    .caisse-card{background:var(--surface);border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 4px 24px var(--brand-glow)!important;overflow:hidden;}
    .caisse-card .card-header{background:var(--surface)!important;border-bottom:1.5px solid var(--border)!important;padding:15px 20px;}
    .status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:99px;font-size:.78rem;font-weight:700;}
    .status-open{background:var(--success-bg);color:var(--success);}
    .status-closed{background:var(--danger-bg);color:var(--danger);}
    .info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);}
    .info-row:last-child{border-bottom:none;}
    .info-label{font-size:.78rem;color:var(--text-muted);font-weight:600;}
    .info-value{font-size:.88rem;color:var(--text);font-weight:700;}
    .pos-input{background:var(--bg)!important;border:1.5px solid var(--border)!important;border-radius:var(--radius-sm)!important;color:var(--text)!important;padding:10px 14px;font-size:.9rem;width:100%;}
    .pos-input:focus{border-color:var(--brand)!important;box-shadow:0 0 0 3px rgba(69,61,222,.1)!important;outline:none;}
    .btn-brand-solid{background:var(--brand)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;padding:11px 26px;font-size:.9rem;}
    .btn-brand-solid:hover{background:var(--brand-dark)!important;}
    .btn-danger-solid{background:var(--danger)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;padding:11px 26px;font-size:.9rem;}
    .hist-table thead th{background:var(--brand-light)!important;color:var(--brand-dark)!important;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;border:none!important;padding:11px 16px;}
    .hist-table tbody td{padding:11px 16px;border-color:var(--border)!important;font-size:.84rem;color:var(--text);vertical-align:middle;}
    .fade-up{opacity:0;transform:translateY(18px);animation:fadeUp .5s ease forwards;}
    .s1{animation-delay:.05s}.s2{animation-delay:.15s}.s3{animation-delay:.25s}
    @keyframes fadeUp{to{opacity:1;transform:translateY(0);}}
    .big-amount{font-size:2rem;font-weight:900;color:var(--brand);letter-spacing:-1px;}
</style>

<div class="d-flex align-items-center mb-4 fade-up s1">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);margin:0;">
            Gestion de <span style="color:var(--brand);">caisse</span>
        </h1>
        <p style="color:var(--text-muted);font-size:.81rem;margin:3px 0 0;">
            Solde d'ouverture et fermeture de session
        </p>
    </div>
</div>

<div class="row g-4">

    <!-- ── Status actuel ── -->
    <div class="col-lg-5 fade-up s1">
        <div class="caisse-card card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-cash-register me-2" style="color:var(--brand);"></i>Session actuelle</h6>
            </div>
            <div class="card-body p-4">
                <?php if ($sessionActive): ?>
                    <div class="text-center mb-4">
                        <span class="status-badge status-open">
                            <span style="width:8px;height:8px;background:var(--success);border-radius:50%;animation:pulse 2s infinite;display:inline-block;"></span>
                            Session ouverte
                        </span>
                    </div>
                    <div class="info-row"><span class="info-label">Caissier</span><span class="info-value"><?= sanitize($sessionActive['caissier'] ?? 'N/A') ?></span></div>
                    <div class="info-row"><span class="info-label">Ouverte à</span><span class="info-value"><?= formatDate($sessionActive['ouvert_a']) ?></span></div>
                    <div class="info-row"><span class="info-label">Solde d'ouverture</span><span class="info-value"><?= formatPrice((float)$sessionActive['solde_ouverture']) ?></span></div>
                    <div class="info-row">
                        <span class="info-label">CA encaissé</span>
                        <span class="info-value" style="color:var(--success);"><?= formatPrice($caEncaisse) ?></span>
                    </div>
                    <div class="text-center mt-4">
                        <div class="big-amount"><?= formatPrice((float)$sessionActive['solde_ouverture'] + $caEncaisse) ?></div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px;">Solde estimé en caisse</div>
                    </div>

                    <hr style="border-color:var(--border);">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:var(--text);">Solde de fermeture (réel)</label>
                        <input type="number" id="soldeFermeture" class="pos-input" placeholder="Montant compté en caisse..." min="0" step="0.01">
                    </div>
                    <button class="btn btn-danger-solid w-100" onclick="fermerSession(<?= $sessionActive['id'] ?>)">
                        <i class="fas fa-lock me-2"></i>Fermer la session
                    </button>
                <?php else: ?>
                    <div class="text-center mb-4">
                        <span class="status-badge status-closed">
                            <i class="fas fa-lock" style="font-size:.7rem;"></i> Session fermée
                        </span>
                    </div>
                    <div class="text-center py-3" style="color:var(--text-muted);">
                        <i class="fas fa-cash-register fa-2x mb-3" style="opacity:.3;display:block;"></i>
                        Aucune session ouverte. Saisissez le solde d'ouverture pour commencer.
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:var(--text);">Solde d'ouverture (HTG)</label>
                        <input type="number" id="soldeOuverture" class="pos-input" placeholder="Ex: 5000.00" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:var(--text);">Notes (optionnel)</label>
                        <textarea id="sessionNotes" class="pos-input" rows="2" placeholder="Remarques sur la session..."></textarea>
                    </div>
                    <button class="btn btn-brand-solid w-100" onclick="ouvrirSession()">
                        <i class="fas fa-unlock me-2"></i>Ouvrir la session
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── Historique sessions ── -->
    <div class="col-lg-7 fade-up s2">
        <div class="caisse-card card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2" style="color:var(--brand);"></i>Historique des sessions</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle hist-table">
                        <thead>
                            <tr><th>Caissier</th><th>Ouverture</th><th>Solde ouv.</th><th>CA enc.</th><th>Fermeture</th><th>Statut</th></tr>
                        </thead>
                        <tbody>
                        <?php if (empty($historique)): ?>
                            <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">Aucune session enregistrée.</td></tr>
                        <?php else: foreach ($historique as $s): ?>
                            <tr>
                                <td class="fw-semibold"><?= sanitize($s['caissier'] ?? 'N/A') ?></td>
                                <td style="font-size:.8rem;color:var(--text-muted);"><?= formatDate($s['ouvert_a']) ?></td>
                                <td style="font-weight:700;"><?= formatPrice((float)$s['solde_ouverture']) ?></td>
                                <td style="color:var(--success);font-weight:700;">
                                    <?= $s['ferme_a'] && $s['solde_fermeture'] !== null
                                        ? formatPrice((float)$s['solde_fermeture'] - (float)$s['solde_ouverture'])
                                        : '—' ?>
                                </td>
                                <td style="font-size:.8rem;color:var(--text-muted);">
                                    <?= $s['ferme_a'] ? formatDate($s['ferme_a']) : '—' ?>
                                </td>
                                <td>
                                    <?php if ($s['ferme_a']): ?>
                                        <span class="badge" style="background:var(--danger-bg);color:var(--danger);font-size:.72rem;">Fermée</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:var(--success-bg);color:var(--success);font-size:.72rem;">Ouverte</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $extraJs = <<<'JS'
async function ouvrirSession() {
    const solde = parseFloat(document.getElementById('soldeOuverture').value) || 0;
    const notes = document.getElementById('sessionNotes').value;
    const res   = await fetch('index.php?page=caisse&action=ouvrir', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({solde_ouverture: solde, notes})
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert('Erreur : ' + data.message);
}

async function fermerSession(id) {
    const solde = parseFloat(document.getElementById('soldeFermeture').value);
    if (isNaN(solde)) { alert('Veuillez saisir le solde de fermeture.'); return; }
    if (!confirm('Confirmer la fermeture de la session ?')) return;
    const res  = await fetch('index.php?page=caisse&action=fermer', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({session_id: id, solde_fermeture: solde})
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert('Erreur : ' + data.message);
}
JS;
?>
<?php require VIEW_PATH . '/partials/footer.php'; ?>
