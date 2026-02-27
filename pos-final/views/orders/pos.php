<?php $pageTitle = 'Nouvelle vente'; require VIEW_PATH . '/partials/header.php'; ?>

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
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 10px; flex-wrap: wrap;
    }
    .pos-card .card-header h6 { color: var(--text); font-size: 0.95rem; font-weight: 700; margin: 0; }
    .pos-card .card-header h6 i { color: var(--brand) !important; }

    /* ════ SEARCH ════ */
    .search-input {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--text) !important;
        font-size: 0.85rem; padding: 8px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%; margin-top: 10px;
    }
    .search-input:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important;
        outline: none;
    }
    .search-input::placeholder { color: var(--text-muted); }

    /* ════ CATEGORY ════ */
    .cat-label {
        font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1px; color: var(--brand); background: var(--brand-light);
        display: inline-block; padding: 3px 10px; border-radius: 99px; margin: 14px 0 8px;
    }

    /* ════ PRODUCT BUTTONS ════ */
    .btn-produit {
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: var(--radius-sm); padding: 10px 12px; cursor: pointer;
        transition: border-color 0.18s, background 0.18s, transform 0.15s, box-shadow 0.18s;
        text-align: left; width: 100%;
    }
    .btn-produit:hover {
        border-color: var(--brand); background: var(--brand-light);
        transform: translateY(-2px); box-shadow: 0 6px 18px var(--brand-glow);
    }
    .btn-produit:active { transform: scale(0.97); }
    .btn-produit .prod-name { font-size: 0.82rem; font-weight: 600; color: var(--text); line-height: 1.3; }
    .btn-produit .prod-price { font-size: 0.82rem; font-weight: 800; color: var(--brand); margin-top: 3px; }

    /* ════ FORM ════ */
    .field-label {
        font-size: 0.78rem; font-weight: 700; color: var(--text);
        text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;
    }
    .pos-input {
        background: var(--bg) !important; border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important; color: var(--text) !important;
        padding: 10px 14px; font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s; width: 100%;
    }
    .pos-input:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important; outline: none;
    }

    /* ════ CART LINES ════ */
    .panier-ligne {
        background: var(--bg); border: 1.5px solid var(--border);
        border-radius: var(--radius-sm); padding: 9px 12px; margin-bottom: 8px;
        display: flex; align-items: center; gap: 8px;
        transition: border-color 0.2s; animation: lineIn 0.2s ease;
    }
    .panier-ligne:hover { border-color: var(--brand-mid); }
    @keyframes lineIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .panier-ligne .line-name { font-size: 0.84rem; font-weight: 600; color: var(--text); flex: 1; min-width: 0; }
    .panier-ligne .line-subtotal { font-size: 0.82rem; font-weight: 800; color: var(--success); min-width: 72px; text-align: right; }

    .qty-wrap { display: flex; align-items: center; gap: 4px; }
    .btn-qty {
        width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid var(--border);
        background: var(--surface); color: var(--text); font-size: 0.9rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: background 0.15s, border-color 0.15s, transform 0.12s; padding: 0;
    }
    .btn-qty:hover { background: var(--brand-light); border-color: var(--brand); color: var(--brand); transform: scale(1.1); }
    .qty-num { font-size: 0.88rem; font-weight: 800; color: var(--text); min-width: 20px; text-align: center; }

    .btn-del {
        width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid #fca5a5;
        background: var(--danger-bg); color: var(--danger); font-size: 0.8rem;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: background 0.15s, transform 0.12s; padding: 0; flex-shrink: 0;
    }
    .btn-del:hover { background: var(--danger); color: #fff; transform: scale(1.1); }

    /* ════ EMPTY STATE ════ */
    #panierVide { text-align: center; padding: 28px 0; }
    .cart-empty-icon {
        width: 52px; height: 52px; background: var(--brand-light); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;
    }
    .cart-empty-icon i { color: var(--brand); font-size: 1.15rem; }

    /* ════ TOTAL ════ */
    .cart-divider { border: none; border-top: 1.5px dashed var(--border); margin: 14px 0; }
    .total-row {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--brand-light); border-radius: var(--radius-sm);
        padding: 12px 16px; margin-bottom: 16px;
    }
    .total-label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--brand-dark); }
    #totalDisplay { font-size: 1.45rem; font-weight: 900; color: var(--brand); letter-spacing: -0.5px; }

    /* ════ BUTTONS ════ */
    .btn-validate {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 12px;
        font-size: 0.9rem; width: 100%; box-shadow: 0 4px 16px var(--brand-glow);
        transition: background 0.2s, transform 0.18s, opacity 0.2s; cursor: pointer;
    }
    .btn-validate:hover:not(:disabled) { background: var(--brand-dark) !important; transform: translateY(-2px); }
    .btn-validate:disabled { opacity: 0.45; cursor: not-allowed; transform: none !important; }

    .btn-clear {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 11px; font-size: 0.9rem; width: 100%;
        transition: border-color 0.2s, color 0.2s; cursor: pointer;
    }
    .btn-clear:hover { border-color: var(--danger) !important; color: var(--danger) !important; }

    /* ════ SUCCESS MODAL ════ */
    .modal-content {
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 20px 60px rgba(69,61,222,0.18) !important;
        overflow: hidden;
    }
    .modal-header {
        background: var(--surface) !important;
        border-bottom: 1.5px solid var(--border) !important;
        padding: 16px 20px;
    }
    .modal-header .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .modal-body { padding: 28px 24px 16px; }
    .modal-footer { border-top: 1.5px solid var(--border) !important; padding: 14px 20px; gap: 10px; }

    .success-ring {
        width: 72px; height: 72px; background: var(--success-bg);
        border-radius: 50%; display: flex; align-items: center;
        justify-content: center; margin: 0 auto 16px;
        animation: ringPop 0.4s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes ringPop {
        from { transform: scale(0.4); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .success-ring i { color: var(--success); font-size: 1.8rem; }

    .modal-cmd-id { font-size: 1.1rem; font-weight: 800; color: var(--brand); }

    .btn-modal-print {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 9px 20px;
        font-size: 0.88rem; transition: background 0.2s; cursor: pointer;
    }
    .btn-modal-print:hover { background: var(--brand-dark) !important; }

    .btn-modal-new {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 9px 20px; font-size: 0.88rem;
        transition: border-color 0.2s, color 0.2s; cursor: pointer;
    }
    .btn-modal-new:hover { border-color: var(--brand) !important; color: var(--brand) !important; }

    /* ════ ANIMATIONS ════ */
    .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.45s ease forwards; }
    .s1 { animation-delay: 0.05s; } .s2 { animation-delay: 0.15s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
</style>

<!-- Page header -->
<div class="d-flex align-items-center mb-4 fade-up s1">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);letter-spacing:-0.4px;margin:0;">
            Nouvelle <span style="color:var(--brand);">vente</span>
        </h1>
        <p style="color:var(--text-muted);font-size:0.81rem;margin:3px 0 0;">
            <i class="fas fa-circle me-1" style="font-size:0.45rem;color:var(--success);vertical-align:middle;"></i>
            Caisse active &nbsp;·&nbsp; <?= date('d/m/Y H:i') ?>
        </p>
    </div>
</div>

<div class="row g-4">

    <!-- ── Catalogue ── -->
    <div class="col-lg-7 fade-up s1">
        <div class="pos-card card border-0 h-100">
            <div class="card-header" style="flex-direction:column;align-items:stretch;">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0"><i class="fas fa-store me-2"></i>Catalogue</h6>
                    <span style="font-size:0.74rem;color:var(--text-muted);"><?= count($produits) ?> produits</span>
                </div>
                <input type="text" id="searchProduit" class="search-input"
                       placeholder="🔍 Rechercher un produit…">
            </div>
            <div class="card-body overflow-auto p-3" style="max-height:65vh">
                <?php
                $cats = [];
                foreach ($produits as $p) $cats[$p['categorie']][] = $p;
                foreach ($cats as $cat => $items):
                ?>
                <div class="cat-label"><?= sanitize($cat) ?></div>
                <div class="row g-2">
                    <?php foreach ($items as $p): ?>
                    <div class="col-6 col-md-4 produit-item" data-nom="<?= sanitize($p['nom']) ?>">
                        <button class="btn-produit"
                            onclick="ajouterProduit(<?= $p['id'] ?>,'<?= addslashes(sanitize($p['nom'])) ?>',<?= $p['prix'] ?>)">
                            <div class="prod-name"><?= sanitize($p['nom']) ?></div>
                            <div class="prod-price"><?= formatPrice((float)$p['prix']) ?></div>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Cart ── -->
    <div class="col-lg-5 fade-up s2">
        <div class="pos-card card border-0">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Panier</h6>
                <span id="cartCount" style="background:var(--brand);color:#fff;font-size:0.72rem;font-weight:700;padding:2px 9px;border-radius:99px;display:none;">0</span>
            </div>
            <div class="card-body p-3">

                <!-- Client -->
                <div class="mb-3">
                    <div class="field-label">Nom du client</div>
                    <input type="text" id="clientName" class="pos-input" value="Client comptoir" placeholder="Nom du client…">
                </div>

                <!-- Empty state -->
                <div id="panierVide">
                    <div class="cart-empty-icon"><i class="fas fa-shopping-basket"></i></div>
                    <div style="font-weight:700;color:var(--text);font-size:0.88rem;">Panier vide</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">Sélectionnez des produits dans le catalogue.</div>
                </div>

                <!-- Lines -->
                <div id="panierLignes"></div>

                <hr class="cart-divider">

                <!-- Total -->
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span id="totalDisplay">0,00 HTG</span>
                </div>

                <!-- Actions -->
                <button class="btn-validate mb-2" onclick="validerCommande()" id="btnValider" disabled>
                    <i class="fas fa-check me-2"></i>Valider la commande
                </button>
                <button class="btn-clear" onclick="viderPanier()">
                    <i class="fas fa-trash me-2"></i>Vider le panier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Success Modal ── -->
<div class="modal fade" id="modalTicket" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title"><i class="fas fa-check-circle me-2 text-success"></i>Commande validée</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="success-ring"><i class="fas fa-check"></i></div>
                <div class="modal-cmd-id mb-1">Commande <span id="modalCmdId"></span></div>
                <p style="color:var(--text-muted);font-size:0.84rem;margin:0;">créée avec succès. Imprimer le ticket ?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn-modal-print" onclick="imprimerTicket()">
                    <i class="fas fa-print me-1"></i>Imprimer
                </button>
                <button class="btn-modal-new" data-bs-dismiss="modal" onclick="reinitialiser()">
                    Nouvelle vente
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$extraJs = <<<'JS'
let panier = [];
let derniereCmdId = null;

function ajouterProduit(id, nom, prix) {
    const ex = panier.find(p => p.id === id);
    if (ex) ex.quantite++;
    else panier.push({ id, produit_nom: nom, quantite: 1, prix_unitaire: prix });
    render();
}

function changerQte(id, delta) {
    const item = panier.find(p => p.id === id);
    if (!item) return;
    item.quantite += delta;
    if (item.quantite <= 0) panier = panier.filter(p => p.id !== id);
    render();
}

function viderPanier() { panier = []; render(); }

function render() {
    const vide  = document.getElementById('panierVide');
    const cont  = document.getElementById('panierLignes');
    const btn   = document.getElementById('btnValider');
    const badge = document.getElementById('cartCount');

    if (!panier.length) {
        vide.style.display = 'block';
        cont.innerHTML = '';
        document.getElementById('totalDisplay').textContent = '0,00 HTG';
        btn.disabled = true;
        badge.style.display = 'none';
        return;
    }
    vide.style.display = 'none';
    btn.disabled = false;
    badge.style.display = 'inline';
    badge.textContent = panier.reduce((s, p) => s + p.quantite, 0);

    let total = 0, html = '';
    panier.forEach(item => {
        const sous = item.quantite * item.prix_unitaire;
        total += sous;
        html += `<div class="panier-ligne">
            <div class="line-name">${item.produit_nom}</div>
            <div class="qty-wrap">
                <button class="btn-qty" onclick="changerQte(${item.id},-1)">−</button>
                <span class="qty-num">${item.quantite}</span>
                <button class="btn-qty" onclick="changerQte(${item.id},1)">+</button>
            </div>
            <div class="line-subtotal">${htg(sous)}</div>
            <button class="btn-del" onclick="panier=panier.filter(p=>p.id!==${item.id});render()" title="Supprimer">
                <i class="fas fa-times" style="font-size:0.7rem;"></i>
            </button>
        </div>`;
    });
    cont.innerHTML = html;
    document.getElementById('totalDisplay').textContent = htg(total);
}

function htg(v) {
    return new Intl.NumberFormat('fr-HT', {
        style: 'currency', currency: 'HTG', currencyDisplay: 'symbol'
    }).format(v);
}

async function validerCommande() {
    const btn    = document.getElementById('btnValider');
    const client = document.getElementById('clientName').value.trim();
    if (!client) { alert('Veuillez saisir le nom du client.'); return; }
    if (!panier.length) { alert('Le panier est vide.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validation…';

    try {
        const res  = await fetch('index.php?page=commande&action=store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ client_name: client, lignes: panier })
        });
        const data = await res.json();
        if (data.success) {
            derniereCmdId = data.commande.id;
            document.getElementById('modalCmdId').textContent = '#' + derniereCmdId;
            new bootstrap.Modal(document.getElementById('modalTicket')).show();
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
        } else {
            alert('Erreur : ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
        }
    } catch(e) {
        alert('Erreur réseau. Réessayez.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
    }
}

function imprimerTicket() {
    if (derniereCmdId) window.open('index.php?page=commande&action=ticket&id='+derniereCmdId, '_blank');
}

function reinitialiser() {
    panier = []; derniereCmdId = null;
    document.getElementById('clientName').value = 'Client comptoir';
    render();
}

document.getElementById('searchProduit').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.produit-item').forEach(el => {
        el.style.display = el.dataset.nom.toLowerCase().includes(q) ? '' : 'none';
    });
});
JS;
?>

<?php require VIEW_PATH . '/partials/footer.php'; ?>