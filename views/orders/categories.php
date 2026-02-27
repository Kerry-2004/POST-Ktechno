<?php $pageTitle = 'Gestion des catégories'; require VIEW_PATH . '/partials/header.php'; ?>
<style>
    :root{--brand:#453dde;--brand-dark:#2d27a8;--brand-light:#eeecfd;--brand-mid:#c7c3f7;--brand-glow:rgba(69,61,222,.13);--success:#16a34a;--success-bg:#dcfce7;--danger:#dc2626;--danger-bg:#fee2e2;--surface:#fff;--bg:#f5f4ff;--border:#e8e6fb;--text:#1a1740;--text-2:#ffffffff;--text-muted:#6b6897;--radius:16px;--radius-sm:10px;}
    body{background:var(--bg)!important;}
    .cat-card{background:var(--surface);border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 4px 24px var(--brand-glow)!important;overflow:hidden;}
    .cat-card .card-header{background:var(--surface)!important;border-bottom:1.5px solid var(--border)!important;padding:15px 20px;}
    .cat-card .card-header h6{color:var(--text);font-size:.95rem;font-weight:700;margin:0;}
    .cat-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border);transition:background .15s;}
    .cat-row:last-child{border-bottom:none;}
    .cat-row:hover{background:var(--bg);}
    .cat-dot{width:18px;height:18px;border-radius:50%;flex-shrink:0;}
    .cat-name{flex:1;font-weight:600;color:var(--text);font-size:.88rem;}
    .btn-xs{padding:5px 10px;font-size:.74rem;border-radius:7px;transition:all .15s;}
    .modal-content{border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;}
    .modal-header{color:var(--text-2);font-weight:700;font-size:.88rem;}
    .form-control-pos{background:var(--bg)!important;border:1.5px solid var(--border)!important;border-radius:var(--radius-sm)!important;color:var(--text)!important;padding:9px 12px;}
    .form-control-pos:focus{border-color:var(--brand)!important;box-shadow:0 0 0 3px rgba(69,61,222,.1)!important;}
    .btn-brand{background:var(--brand)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;}
    .btn-brand:hover{background:var(--brand-dark)!important;}
    .fade-up{opacity:0;transform:translateY(18px);animation:fadeUp .5s ease forwards;}
    @keyframes fadeUp{to{opacity:1;transform:translateY(0);}}
</style>

<div class="d-flex align-items-center justify-content-between mb-4 fade-up">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);margin:0;">Gestion des <span style="color:var(--brand);">catégories</span></h1>
        <p style="color:var(--text-muted);font-size:.81rem;margin:3px 0 0;"><?= count($categories) ?> catégorie(s) au total</p>
    </div>
    <button class="btn btn-brand px-4" onclick="openModal()">
        <i class="fas fa-plus me-2"></i>Nouvelle catégorie
    </button>
</div>

<div class="cat-card fade-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6><i class="fas fa-tags me-2" style="color:var(--brand);"></i>Catégories</h6>
        <span style="font-size:.75rem;color:var(--text-muted);"><?= count($categories) ?> au total</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="fas fa-tags fa-2x mb-3" style="opacity:.3;"></i>
                <div>Aucune catégorie. Créez-en une !</div>
            </div>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
            <div class="cat-row" id="cat-row-<?= $cat['id'] ?>">
                <div class="cat-dot" style="background:<?= sanitize($cat['couleur']) ?>;"></div>
                <div class="cat-name"><?= sanitize($cat['nom']) ?></div>
                <div style="font-size:.75rem;color:var(--text-muted);"><?= sanitize($cat['couleur']) ?></div>
                <button class="btn btn-xs btn-outline-warning" onclick="editCat(<?= $cat['id'] ?>, '<?= addslashes(sanitize($cat['nom'])) ?>', '<?= sanitize($cat['couleur']) ?>')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-xs btn-outline-danger" onclick="deleteCat(<?= $cat['id'] ?>, '<?= addslashes(sanitize($cat['nom'])) ?>')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Ajouter/Modifier -->
<div class="modal fade" id="modalCat" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold" id="modalCatTitle">Nouvelle catégorie</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="mb-3">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--text-2);text-transform:uppercase;">Nom</label>
                    <input type="text" id="catNom" class="form-control form-control-pos" placeholder="Ex: Boissons">
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--text-2);text-transform:uppercase;">Couleur</label>
                    <input type="color" id="catCouleur" class="form-control" value="#453dde" style="height:40px;padding:4px;border-radius:8px;border:1.5px solid var(--border);">
                </div>
                <div id="catError" class="alert alert-danger d-none py-2 px-3" style="font-size:.82rem;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-brand w-100" onclick="saveCat()"><i class="fas fa-save me-2"></i>Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<?php $extraJs = <<<'JS'
let editingId = 0;

function openModal(id=0, nom='', couleur='#453dde') {
    editingId = id;
    document.getElementById('modalCatTitle').textContent = id ? 'Modifier la catégorie' : 'Nouvelle catégorie';
    document.getElementById('catNom').value    = nom;
    document.getElementById('catCouleur').value = couleur;
    document.getElementById('catError').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('modalCat')).show();
}

function editCat(id, nom, couleur) { openModal(id, nom, couleur); }

async function saveCat() {
    const nom     = document.getElementById('catNom').value.trim();
    const couleur = document.getElementById('catCouleur').value;
    const errEl   = document.getElementById('catError');
    if (!nom) { errEl.textContent = 'Le nom est requis.'; errEl.classList.remove('d-none'); return; }

    const url = editingId
        ? `index.php?page=categories&action=update&id=${editingId}`
        : 'index.php?page=categories&action=store';

    const res  = await fetch(url, {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({nom,couleur})});
    const data = await res.json();
    if (data.success) {
        location.reload();
    } else {
        errEl.textContent = data.message;
        errEl.classList.remove('d-none');
    }
}

async function deleteCat(id, nom) {
    if (!confirm(`Supprimer la catégorie "${nom}" ? Les produits seront déplacés vers "Général".`)) return;
    const res  = await fetch(`index.php?page=categories&action=delete&id=${id}`, {method:'POST'});
    const data = await res.json();
    if (data.success) {
        document.getElementById('cat-row-'+id).remove();
    } else {
        alert('Erreur : ' + data.message);
    }
}
JS;
?>
<?php require VIEW_PATH . '/partials/footer.php'; ?>
