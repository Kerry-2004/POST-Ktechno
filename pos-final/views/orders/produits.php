<?php defined('VIEW_PATH') || die(header('Location: ../../index.php'));
$pageTitle = 'Produits'; require VIEW_PATH . '/partials/header.php'; ?>

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
        font-size: 0.75rem; font-weight: 700; color:white;
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

    .btn-ghost {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 9px 18px; font-size: 0.875rem;
        transition: border-color 0.2s, color 0.2s; cursor: pointer;
    }
    .btn-ghost:hover { border-color: var(--brand) !important; color: var(--brand) !important; }

    /* ════ SEARCH ════ */
    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem; pointer-events: none; }
    .search-input {
        background: var(--bg) !important; border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important; color: var(--text) !important;
        padding: 8px 13px 8px 34px; font-size: 0.85rem; width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input:focus { border-color: var(--brand) !important; box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important; outline: none; }

    /* ════ COUNT BADGE ════ */
    .count-badge { background: var(--brand-light); color: var(--brand); font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 99px; margin-left: 6px; }

    /* ════ TABLE ════ */
    .products-table thead th {
        background: var(--brand-light) !important; color: var(--brand-dark) !important;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; border: none !important; padding: 11px 16px;
    }
    .products-table tbody td {
        padding: 13px 16px; border-color: var(--border) !important;
        font-size: 0.875rem; vertical-align: middle; color: var(--text);
    }
    .products-table tbody tr:hover td { background: var(--brand-light) !important; }

    .badge-id { background: var(--brand-light); color: var(--brand); font-weight: 700; font-size: 0.74rem; padding: 4px 9px; border-radius: 6px; }
    .price-cell { color: var(--success); font-weight: 700; }

    /* Status toggle */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.74rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; cursor: pointer;
        transition: opacity 0.2s, transform 0.15s; border: none; outline: none;
    }
    .status-pill.actif   { background: var(--success-bg); color: var(--success); }
    .status-pill.inactif { background: #f3f4f6; color: #9ca3af; }
    .status-pill:hover   { opacity: 0.8; transform: scale(1.05); }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .status-pill.actif   .status-dot { background: var(--success); }
    .status-pill.inactif .status-dot { background: #9ca3af; }

    /* Cat pill */
    .cat-pill { background: var(--brand-light); color: var(--brand); font-size: 0.74rem; font-weight: 600; padding: 3px 9px; border-radius: 99px; }

    /* Action buttons */
    .btn-xs { padding: 5px 10px; font-size: 0.74rem; border-radius: 7px; transition: all 0.15s; }
    .btn-xs.btn-edit  { border: 1.5px solid var(--warning) !important; color: var(--warning) !important; background: var(--warning-bg) !important; }
    .btn-xs.btn-edit:hover  { background: var(--warning) !important; color: #fff !important; transform: scale(1.08); }
    .btn-xs.btn-del   { border: 1.5px solid #fca5a5 !important; color: var(--danger) !important; background: var(--danger-bg) !important; }
    .btn-xs.btn-del:hover   { background: var(--danger) !important; border-color: var(--danger) !important; color: #fff !important; transform: scale(1.08); }

    /* ════ EMPTY STATE ════ */
    .empty-state-icon { width: 56px; height: 56px; background: var(--brand-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
    .empty-state-icon i { color: var(--brand); font-size: 1.2rem; }

    /* ════ MODAL ════ */
    .modal-content { border: 1.5px solid var(--border) !important; border-radius: var(--radius) !important; box-shadow: 0 20px 60px rgba(69,61,222,0.18) !important; overflow: hidden; }
    .modal-header { background: var(--surface) !important; border-bottom: 1.5px solid var(--border) !important; padding: 16px 20px; }
    .modal-header .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .modal-body { padding: 20px; }
    .modal-footer { border-top: 1.5px solid var(--border) !important; padding: 14px 20px; gap: 10px; }

    /* Delete modal */
    .danger-ring { width: 60px; height: 60px; background: var(--danger-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; animation: ringPop 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
    @keyframes ringPop { from { transform: scale(0.4); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .danger-ring i { color: var(--danger); font-size: 1.4rem; }
    .btn-confirm-del { background: var(--danger) !important; border: none !important; color: #fff !important; border-radius: var(--radius-sm) !important; font-weight: 700; padding: 9px 20px; font-size: 0.875rem; cursor: pointer; transition: background 0.2s; }
    .btn-confirm-del:hover { background: #b91c1c !important; }
    .btn-modal-ghost { background: var(--surface) !important; border: 1.5px solid var(--border) !important; color: var(--text-muted) !important; border-radius: var(--radius-sm) !important; font-weight: 600; padding: 9px 20px; font-size: 0.875rem; cursor: pointer; transition: border-color 0.2s, color 0.2s; }
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
            Gestion des <span style="color:var(--brand);">produits</span>
        </h1>
        <p style="color:var(--text-muted);font-size:0.81rem;margin:3px 0 0;">
            Ajouter, modifier et supprimer les produits du catalogue.
        </p>
    </div>
    <button class="btn-brand-solid" onclick="ouvrirModalAjout()">
        <i class="fas fa-plus"></i>Nouveau produit
    </button>
</div>

<!-- Table card -->
<div class="pos-card card border-0 fade-up s2">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-box-open me-2"></i>Catalogue
            <span class="count-badge" id="produitCount"><?= count($produits) ?></span>
        </h6>
        <div style="width:220px;">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchProduit" class="search-input" placeholder="Rechercher…">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle products-table" id="produitsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="produitsBody">
                <?php if (empty($produits)): ?>
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
                            <div style="font-weight:700;color:var(--text);font-size:0.9rem;">Aucun produit</div>
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:4px;">Cliquez sur « Nouveau produit » pour commencer.</div>
                        </td>
                    </tr>
                <?php else: foreach ($produits as $p): ?>
                <tr data-id="<?= $p['id'] ?>"
                    data-nom="<?= htmlspecialchars($p['nom'], ENT_QUOTES) ?>"
                    data-prix="<?= $p['prix'] ?>"
                    data-cat="<?= htmlspecialchars($p['categorie'], ENT_QUOTES) ?>"
                    data-actif="<?= $p['actif'] ?>">
                    <td><span class="badge-id"><?= $p['id'] ?></span></td>
                    <td class="fw-semibold"><?= sanitize($p['nom']) ?></td>
                    <td><span class="cat-pill"><?= sanitize($p['categorie']) ?></span></td>
                    <td class="price-cell"><?= formatPrice((float)$p['prix']) ?></td>
                    <td>
                        <button class="status-pill <?= $p['actif'] ? 'actif' : 'inactif' ?>"
                                onclick="toggleActif(<?= $p['id'] ?>, this)"
                                title="Cliquer pour basculer">
                            <span class="status-dot"></span>
                            <?= $p['actif'] ? 'Actif' : 'Inactif' ?>
                        </button>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-xs btn-edit me-1" onclick="ouvrirModalEdit(this.closest('tr'))" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-xs btn-del" onclick="confirmerSuppression(<?= $p['id'] ?>, '<?= addslashes(sanitize($p['nom'])) ?>')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ════ Add / Edit Modal ════ -->
<div class="modal fade" id="modalProduit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalProduitTitle">
                    <i class="fas fa-plus-circle me-2" style="color:var(--brand);"></i>Nouveau produit
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="field-label">Nom du produit <span style="color:var(--danger);">*</span></label>
                        <input type="text" id="editNom" class="pos-input" placeholder="Ex: Café noir, Sandwich thon…" required>
                    </div>
                    <div class="col-md-6">
                        <label class="field-label">Prix (HTG) <span style="color:var(--danger);">*</span></label>
                        <input type="number" id="editPrix" class="pos-input" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="field-label">Catégorie</label>
                        <input type="text" id="editCategorie" class="pos-input" placeholder="Ex: Boissons, Plats…" list="catSuggestions">
                        <datalist id="catSuggestions"></datalist>
                    </div>
                    <div class="col-12">
                        <label class="field-label">Statut</label>
                        <select id="editActif" class="pos-select">
                            <option value="1">Actif – visible dans le catalogue</option>
                            <option value="0">Inactif – masqué du catalogue</option>
                        </select>
                    </div>
                </div>
                <div id="formError" style="display:none;margin-top:14px;background:var(--danger-bg);color:var(--danger);border-radius:var(--radius-sm);padding:10px 14px;font-size:0.84rem;font-weight:600;">
                    <i class="fas fa-exclamation-circle me-2"></i><span id="formErrorMsg"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal-ghost" data-bs-dismiss="modal">Annuler</button>
                <button class="btn-brand-solid" id="btnSauvegarder" onclick="sauvegarder()">
                    <i class="fas fa-save me-1"></i>Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ════ Delete Confirm Modal ════ -->
<div class="modal fade" id="modalSupprimer" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title"><i class="fas fa-trash me-2 text-danger"></i>Supprimer le produit</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="danger-ring"><i class="fas fa-exclamation"></i></div>
                <div style="font-weight:700;color:var(--text);font-size:0.95rem;margin-bottom:6px;">
                    <span id="delNom" style="color:var(--brand);"></span>
                </div>
                <p style="color:var(--text-muted);font-size:0.84rem;margin:0;">
                    Cette action est irréversible. Supprimer ce produit ?
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-2 border-0">
                <button class="btn-modal-ghost" data-bs-dismiss="modal">Annuler</button>
                <button class="btn-confirm-del" id="btnConfirmDel">
                    <i class="fas fa-trash me-1"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$extraJs = <<<'JS'
const modalProduit  = new bootstrap.Modal(document.getElementById('modalProduit'));
const modalSuppr    = new bootstrap.Modal(document.getElementById('modalSupprimer'));
let   deleteTargetId = null;

/* ── Populate category datalist ── */
function refreshCatSuggestions() {
    const cats = [...new Set([...document.querySelectorAll('#produitsBody tr[data-cat]')].map(r => r.dataset.cat))];
    const dl = document.getElementById('catSuggestions');
    dl.innerHTML = cats.map(c => `<option value="${c}">`).join('');
}
refreshCatSuggestions();

/* ── Open Add modal ── */
function ouvrirModalAjout() {
    document.getElementById('editId').value       = '';
    document.getElementById('editNom').value      = '';
    document.getElementById('editPrix').value     = '';
    document.getElementById('editCategorie').value= 'Général';
    document.getElementById('editActif').value    = '1';
    document.getElementById('modalProduitTitle').innerHTML = '<i class="fas fa-plus-circle me-2" style="color:var(--brand);"></i>Nouveau produit';
    document.getElementById('btnSauvegarder').innerHTML    = '<i class="fas fa-save me-1"></i>Ajouter';
    hideError();
    modalProduit.show();
}

/* ── Open Edit modal ── */
function ouvrirModalEdit(row) {
    document.getElementById('editId').value        = row.dataset.id;
    document.getElementById('editNom').value       = row.dataset.nom;
    document.getElementById('editPrix').value      = row.dataset.prix;
    document.getElementById('editCategorie').value = row.dataset.cat;
    document.getElementById('editActif').value     = row.dataset.actif;
    document.getElementById('modalProduitTitle').innerHTML = '<i class="fas fa-edit me-2" style="color:var(--warning);"></i>Modifier le produit';
    document.getElementById('btnSauvegarder').innerHTML    = '<i class="fas fa-save me-1"></i>Sauvegarder';
    hideError();
    modalProduit.show();
}

/* ── Save (add or edit) ── */
async function sauvegarder() {
    const id   = document.getElementById('editId').value;
    const nom  = document.getElementById('editNom').value.trim();
    const prix = parseFloat(document.getElementById('editPrix').value);
    const cat  = document.getElementById('editCategorie').value.trim() || 'Général';
    const actif= document.getElementById('editActif').value;

    if (!nom)           { showError('Le nom du produit est requis.'); return; }
    if (isNaN(prix) || prix < 0) { showError('Veuillez saisir un prix valide.'); return; }

    const btn = document.getElementById('btnSauvegarder');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>En cours…';

    const url    = id ? `index.php?page=produits&action=update&id=${id}` : 'index.php?page=produits&action=store';
    const method = 'POST';

    try {
        const res  = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nom, prix, categorie: cat, actif: parseInt(actif) })
        });
        const data = await res.json();
        if (data.success) {
            modalProduit.hide();
            location.reload();
        } else {
            showError(data.message || 'Une erreur est survenue.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Sauvegarder';
        }
    } catch(e) {
        showError('Erreur réseau. Réessayez.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Sauvegarder';
    }
}

/* ── Toggle actif ── */
async function toggleActif(id, btn) {
    const isActif = btn.classList.contains('actif');
    const newVal  = isActif ? 0 : 1;
    btn.style.opacity = '0.5';
    try {
        const res  = await fetch(`index.php?page=produits&action=toggle&id=${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ actif: newVal })
        });
        const data = await res.json();
        if (data.success) {
            btn.classList.toggle('actif',   newVal === 1);
            btn.classList.toggle('inactif', newVal === 0);
            btn.innerHTML = `<span class="status-dot"></span>${newVal ? 'Actif' : 'Inactif'}`;
            btn.closest('tr').dataset.actif = newVal;
        }
    } catch(e) { /* silent fail */ }
    btn.style.opacity = '1';
}

/* ── Delete ── */
function confirmerSuppression(id, nom) {
    deleteTargetId = id;
    document.getElementById('delNom').textContent = nom;
    modalSuppr.show();
}

document.getElementById('btnConfirmDel').addEventListener('click', async function() {
    if (!deleteTargetId) return;
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Suppression…';
    try {
        const res  = await fetch(`index.php?page=produits&action=delete&id=${deleteTargetId}`, { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            modalSuppr.hide();
            const row = document.querySelector(`#produitsBody tr[data-id="${deleteTargetId}"]`);
            if (row) { row.style.opacity='0'; row.style.transition='opacity 0.3s'; setTimeout(()=>row.remove(), 300); }
            updateCount();
        } else {
            alert('Erreur : ' + data.message);
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer';
        }
    } catch(e) {
        alert('Erreur réseau.');
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer';
    }
});

/* ── Search ── */
document.getElementById('searchProduit').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    let visible = 0;
    document.querySelectorAll('#produitsBody tr[data-id]').forEach(row => {
        const match = row.dataset.nom.toLowerCase().includes(q) || row.dataset.cat.toLowerCase().includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('produitCount').textContent = visible;
});

/* ── Helpers ── */
function showError(msg) {
    document.getElementById('formError').style.display = 'block';
    document.getElementById('formErrorMsg').textContent = msg;
}
function hideError() {
    document.getElementById('formError').style.display = 'none';
}
function updateCount() {
    const n = document.querySelectorAll('#produitsBody tr[data-id]').length;
    document.getElementById('produitCount').textContent = n;
}

/* Submit on Enter in modal */
document.getElementById('modalProduit').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sauvegarder(); }
});
JS;
?>
<?php require VIEW_PATH . '/partials/footer.php'; ?>