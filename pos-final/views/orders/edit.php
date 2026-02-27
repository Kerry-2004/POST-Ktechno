<?php $pageTitle = 'Modifier commande #'.$commande['id']; require VIEW_PATH . '/partials/header.php'; ?>

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
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .pos-card .card-header h6 {
        color: var(--text); font-size: 0.95rem; font-weight: 700; margin: 0;
    }
    .pos-card .card-header h6 i { color: var(--brand) !important; }

    /* ════ SEARCH INPUT ════ */
    .search-input {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--text) !important;
        font-size: 0.85rem;
        padding: 8px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
        margin-top: 10px;
    }
    .search-input:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important;
        outline: none;
    }
    .search-input::placeholder { color: var(--text-muted); }

    /* ════ CATEGORY LABEL ════ */
    .cat-label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--brand);
        background: var(--brand-light);
        display: inline-block;
        padding: 3px 10px;
        border-radius: 99px;
        margin: 14px 0 8px;
    }

    /* ════ PRODUCT BUTTONS ════ */
    .btn-produit {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 10px 12px;
        cursor: pointer;
        transition: border-color 0.18s, background 0.18s, transform 0.15s, box-shadow 0.18s;
        text-align: left;
        width: 100%;
    }
    .btn-produit:hover {
        border-color: var(--brand);
        background: var(--brand-light);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px var(--brand-glow);
    }
    .btn-produit:active { transform: scale(0.97); }
    .btn-produit .prod-name { font-size: 0.82rem; font-weight: 600; color: var(--text); line-height: 1.3; }
    .btn-produit .prod-price { font-size: 0.82rem; font-weight: 800; color: var(--brand); margin-top: 3px; }

    /* ════ FORM ELEMENTS ════ */
    .field-label {
        font-size: 0.78rem; font-weight: 700; color: var(--text);
        text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px;
    }
    .pos-input {
        background: var(--bg) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--text) !important;
        padding: 10px 14px; font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }
    .pos-input:focus {
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(69,61,222,0.1) !important;
        outline: none;
    }

    /* ════ CART LINE ════ */
    .panier-ligne {
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 9px 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: border-color 0.2s;
        animation: lineIn 0.2s ease;
    }
    .panier-ligne:hover { border-color: var(--brand-mid); }
    @keyframes lineIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .panier-ligne .line-name { font-size: 0.84rem; font-weight: 600; color: var(--text); flex: 1; min-width: 0; }
    .panier-ligne .line-subtotal { font-size: 0.82rem; font-weight: 800; color: var(--success); min-width: 72px; text-align: right; }

    /* Qty controls */
    .qty-wrap { display: flex; align-items: center; gap: 4px; }
    .btn-qty {
        width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid var(--border);
        background: var(--surface); color: var(--text); font-size: 0.9rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: background 0.15s, border-color 0.15s, transform 0.12s;
        padding: 0;
    }
    .btn-qty:hover { background: var(--brand-light); border-color: var(--brand); color: var(--brand); transform: scale(1.1); }
    .qty-num { font-size: 0.88rem; font-weight: 800; color: var(--text); min-width: 20px; text-align: center; }

    /* Delete btn */
    .btn-del {
        width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid #fca5a5;
        background: var(--danger-bg); color: var(--danger); font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: background 0.15s, transform 0.12s; padding: 0; flex-shrink: 0;
    }
    .btn-del:hover { background: var(--danger); color: #fff; transform: scale(1.1); }

    /* ════ DIVIDER ════ */
    .cart-divider { border: none; border-top: 1.5px dashed var(--border); margin: 14px 0; }

    /* ════ TOTAL ════ */
    .total-row {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--brand-light); border-radius: var(--radius-sm);
        padding: 12px 16px; margin-bottom: 16px;
    }
    .total-label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--brand-dark); }
    #totalDisplay { font-size: 1.45rem; font-weight: 900; color: var(--brand); letter-spacing: -0.5px; }

    /* ════ ACTION BUTTONS ════ */
    .btn-save {
        background: var(--brand) !important; border: none !important; color: #fff !important;
        border-radius: var(--radius-sm) !important; font-weight: 700; padding: 12px;
        font-size: 0.9rem; width: 100%; box-shadow: 0 4px 16px var(--brand-glow);
        transition: background 0.2s, transform 0.18s; cursor: pointer;
    }
    .btn-save:hover { background: var(--brand-dark) !important; transform: translateY(-2px); }

    .btn-print {
        background: var(--surface) !important; border: 1.5px solid var(--border) !important;
        color: var(--text-muted) !important; border-radius: var(--radius-sm) !important;
        font-weight: 600; padding: 11px; font-size: 0.9rem; width: 100%;
        transition: border-color 0.2s, color 0.2s, transform 0.18s;
        text-align: center; display: block; text-decoration: none;
    }
    .btn-print:hover { border-color: var(--brand) !important; color: var(--brand) !important; transform: translateY(-2px); }

    /* Back button */
    .btn-back {
        font-size: 0.78rem; font-weight: 600; color: var(--text-muted) !important;
        background: var(--bg) !important; border: 1.5px solid var(--border) !important;
        border-radius: 8px; padding: 5px 13px;
        transition: border-color 0.2s, color 0.2s;
        text-decoration: none;
    }
    .btn-back:hover { border-color: var(--brand) !important; color: var(--brand) !important; }

    /* Order badge */
    .order-badge {
        background: var(--brand-light); color: var(--brand); font-size: 0.75rem;
        font-weight: 700; padding: 3px 10px; border-radius: 99px;
    }

    /* Empty cart */
    .cart-empty {
        text-align: center; padding: 24px 0;
    }
    .cart-empty-icon {
        width: 48px; height: 48px; background: var(--brand-light); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;
    }
    .cart-empty-icon i { color: var(--brand); font-size: 1.1rem; }

    /* ════ ANIMATIONS ════ */
    .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.45s ease forwards; }
    .s1 { animation-delay: 0.05s; } .s2 { animation-delay: 0.15s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
</style>

<!-- Page title -->
<div class="d-flex align-items-center gap-3 mb-4 fade-up s1">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);letter-spacing:-0.4px;margin:0;">
            Modifier commande <span style="color:var(--brand);">#<?= $commande['id'] ?></span>
        </h1>
        <p style="color:var(--text-muted);font-size:0.81rem;margin:3px 0 0;">
            <i class="fas fa-user me-1"></i><?= sanitize($commande['client_name']) ?>
            &nbsp;·&nbsp;
            <i class="fas fa-calendar me-1"></i><?= formatDate($commande['created_at']) ?>
        </p>
    </div>
</div>

<div class="row g-4">

    <!-- ── Left: Catalogue ── -->
    <div class="col-lg-7 fade-up s1">
        <div class="pos-card card border-0">
            <div class="card-header" style="flex-direction:column;align-items:stretch;">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-store me-2"></i>Catalogue
                    </h6>
                    <span style="font-size:0.75rem;color:var(--text-muted);"><?= count($produits) ?> produits</span>
                </div>
                <input type="text" id="searchProduit" class="search-input"
                       placeholder="🔍 Rechercher un produit…">
            </div>
            <div class="card-body overflow-auto p-3" style="max-height:60vh">
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
                            onclick="ajouterProduit('<?= addslashes(sanitize($p['nom'])) ?>',<?= $p['prix'] ?>)">
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

    <!-- ── Right: Order panel ── -->
    <div class="col-lg-5 fade-up s2">
        <div class="pos-card card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-edit me-2"></i>
                    Commande <span class="order-badge ms-1">#<?= $commande['id'] ?></span>
                </h6>
                <a href="index.php?page=history" class="btn-back">← Retour</a>
            </div>
            <div class="card-body p-3">

                <!-- Client name -->
                <div class="mb-3">
                    <div class="field-label">Nom du client</div>
                    <input type="text" id="clientName" class="pos-input"
                           value="<?= sanitize($commande['client_name']) ?>"
                           placeholder="Nom du client…">
                </div>

                <!-- Cart lines -->
                <div class="field-label mb-2">Articles</div>
                <div id="panierLignes"></div>

                <hr class="cart-divider">

                <!-- Total -->
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span id="totalDisplay">0,00 HTG</span>
                </div>

                <!-- Buttons -->
                <button class="btn-save mb-2" onclick="sauvegarder()">
                    <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                </button>
                <a href="index.php?page=commande&action=ticket&id=<?= $commande['id'] ?>"
                   class="btn-print" target="_blank">
                    <i class="fas fa-print me-2"></i>Imprimer le ticket
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$lignesJson = json_encode(array_values(array_map(fn($l) => [
    'produit_nom'   => $l['produit_nom'],
    'quantite'      => (int)$l['quantite'],
    'prix_unitaire' => (float)$l['prix_unitaire'],
], $commande['lignes'])));
$cmdId = $commande['id'];
$extraJs = <<<JS
let panier = $lignesJson;
render();

function ajouterProduit(nom, prix) {
    const ex = panier.find(p => p.produit_nom === nom);
    if (ex) ex.quantite++;
    else panier.push({ produit_nom: nom, quantite: 1, prix_unitaire: prix });
    render();
}

function changerQte(i, delta) {
    panier[i].quantite += delta;
    if (panier[i].quantite <= 0) panier.splice(i, 1);
    render();
}

function render() {
    const cont = document.getElementById('panierLignes');
    if (!panier.length) {
        cont.innerHTML = `<div class="cart-empty">
            <div class="cart-empty-icon"><i class="fas fa-shopping-basket"></i></div>
            <div style="font-weight:700;color:var(--text);font-size:0.88rem;">Panier vide</div>
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">Ajoutez des produits depuis le catalogue.</div>
        </div>`;
        document.getElementById('totalDisplay').textContent = '0,00 HTG';
        return;
    }
    let total = 0, html = '';
    panier.forEach((item, i) => {
        const sous = item.quantite * item.prix_unitaire;
        total += sous;
        html += `<div class="panier-ligne">
            <div class="line-name">\${item.produit_nom}</div>
            <div class="qty-wrap">
                <button class="btn-qty" onclick="changerQte(\${i},-1)">−</button>
                <span class="qty-num">\${item.quantite}</span>
                <button class="btn-qty" onclick="changerQte(\${i},1)">+</button>
            </div>
            <div class="line-subtotal">\${htg(sous)}</div>
            <button class="btn-del" onclick="panier.splice(\${i},1);render()" title="Supprimer">
                <i class="fas fa-times" style="font-size:0.7rem;"></i>
            </button>
        </div>`;
    });
    cont.innerHTML = html;
    document.getElementById('totalDisplay').textContent = htg(total);
}

function htg(v) {
    return new Intl.NumberFormat('fr-HT', {
        style: 'currency',
        currency: 'HTG',
        currencyDisplay: 'symbol'
    }).format(v);
}

async function sauvegarder() {
    const btn = document.querySelector('.btn-save');
    const client = document.getElementById('clientName').value.trim();
    if (!client || !panier.length) {
        alert('Client et au moins un produit requis.');
        return;
    }
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde…';
    try {
        const res  = await fetch('index.php?page=commande&action=update&id=$cmdId', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ client_name: client, lignes: panier })
        });
        const data = await res.json();
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Sauvegardé !';
            btn.style.background = '#16a34a';
            setTimeout(() => { location.href = 'index.php?page=history'; }, 800);
        } else {
            alert('Erreur : ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-2"></i>Sauvegarder les modifications';
        }
    } catch(e) {
        alert('Erreur réseau.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Sauvegarder les modifications';
    }
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