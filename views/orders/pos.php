<?php $pageTitle = 'Nouvelle vente'; require VIEW_PATH . '/partials/header.php'; ?>

<style>
    :root {
        --brand:#453dde;--brand-dark:#2d27a8;--brand-light:#eeecfd;--brand-mid:#c7c3f7;--brand-glow:rgba(69,61,222,.13);
        --success:#16a34a;--success-bg:#dcfce7;--danger:#dc2626;--danger-bg:#fee2e2;--warning:#d97706;--warning-bg:#fef3c7;
        --surface:#fff;--bg:#f5f4ff;--border:#e8e6fb;--text:#1a1740;--text-muted:#6b6897;--radius:16px;--radius-sm:10px;
    }
    body{background:var(--bg)!important;}
    .pos-card{background:var(--surface);border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 4px 24px var(--brand-glow)!important;overflow:hidden;}
    .pos-card .card-header{background:var(--surface)!important;border-bottom:1.5px solid var(--border)!important;padding:15px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:10px;flex-wrap:wrap;}
    .pos-card .card-header h6{color:var(--text);font-size:.95rem;font-weight:700;margin:0;}
    .search-input{background:var(--bg)!important;border:1.5px solid var(--border)!important;border-radius:var(--radius-sm)!important;color:var(--text)!important;font-size:.85rem;padding:8px 14px;transition:border-color .2s,box-shadow .2s;width:100%;margin-top:10px;}
    .search-input:focus{border-color:var(--brand)!important;box-shadow:0 0 0 3px rgba(69,61,222,.1)!important;outline:none;}
    .cat-label{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--brand);background:var(--brand-light);display:inline-block;padding:3px 10px;border-radius:99px;margin:14px 0 8px;}
    .btn-produit{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;cursor:pointer;transition:border-color .18s,background .18s,transform .15s,box-shadow .18s;text-align:left;width:100%;position:relative;}
    .btn-produit:hover{border-color:var(--brand);background:var(--brand-light);transform:translateY(-2px);box-shadow:0 6px 18px var(--brand-glow);}
    .btn-produit:active{transform:scale(.97);}
    .btn-produit .prod-name{font-size:.82rem;font-weight:600;color:var(--text);line-height:1.3;}
    .btn-produit .prod-price{font-size:.82rem;font-weight:800;color:var(--brand);margin-top:3px;}
    .stock-badge{position:absolute;top:6px;right:6px;font-size:.62rem;font-weight:700;padding:2px 6px;border-radius:99px;}
    .stock-ok{background:var(--success-bg);color:var(--success);}
    .stock-low{background:var(--warning-bg);color:var(--warning);}
    .stock-out{background:var(--danger-bg);color:var(--danger);}
    .field-label{font-size:.78rem;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;}
    .pos-input{background:var(--bg)!important;border:1.5px solid var(--border)!important;border-radius:var(--radius-sm)!important;color:var(--text)!important;padding:10px 14px;font-size:.9rem;transition:border-color .2s,box-shadow .2s;width:100%;}
    .pos-input:focus{border-color:var(--brand)!important;box-shadow:0 0 0 3px rgba(69,61,222,.1)!important;outline:none;}
    .panier-ligne{background:var(--bg);border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;margin-bottom:8px;display:flex;align-items:center;gap:8px;transition:border-color .2s;animation:lineIn .2s ease;}
    .panier-ligne:hover{border-color:var(--brand-mid);}
    @keyframes lineIn{from{opacity:0;transform:translateY(-6px);}to{opacity:1;transform:translateY(0);}}
    .panier-ligne .line-name{font-size:.84rem;font-weight:600;color:var(--text);flex:1;min-width:0;}
    .panier-ligne .line-subtotal{font-size:.82rem;font-weight:800;color:var(--success);min-width:72px;text-align:right;}
    .qty-wrap{display:flex;align-items:center;gap:4px;}
    .btn-qty{width:26px;height:26px;border-radius:7px;border:1.5px solid var(--border);background:var(--surface);color:var(--text);font-size:.9rem;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s,border-color .15s,transform .12s;padding:0;}
    .btn-qty:hover{background:var(--brand-light);border-color:var(--brand);color:var(--brand);transform:scale(1.1);}
    .qty-num{font-size:.88rem;font-weight:800;color:var(--text);min-width:20px;text-align:center;}
    .btn-del{width:26px;height:26px;border-radius:7px;border:1.5px solid #fca5a5;background:var(--danger-bg);color:var(--danger);font-size:.8rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s,transform .12s;padding:0;flex-shrink:0;}
    .btn-del:hover{background:var(--danger);color:#fff;transform:scale(1.1);}
    #panierVide{text-align:center;padding:28px 0;}
    .cart-empty-icon{width:52px;height:52px;background:var(--brand-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
    .cart-empty-icon i{color:var(--brand);font-size:1.15rem;}
    .cart-divider{border:none;border-top:1.5px dashed var(--border);margin:14px 0;}
    .total-row{display:flex;align-items:center;justify-content:space-between;background:var(--brand-light);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:10px;}
    .total-label{font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--brand-dark);}
    #totalDisplay{font-size:1.45rem;font-weight:900;color:var(--brand);letter-spacing:-.5px;}
    .rendu-row{display:flex;align-items:center;justify-content:space-between;background:var(--success-bg);border-radius:var(--radius-sm);padding:10px 16px;margin-bottom:10px;transition:all .3s;}
    .payment-section{background:var(--bg);border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:14px;margin-bottom:14px;}
    .pm-btn{flex:1;padding:8px;border:1.5px solid var(--border);border-radius:8px;background:var(--surface);font-size:.78rem;font-weight:700;color:var(--text-muted);cursor:pointer;transition:all .15s;text-align:center;}
    .pm-btn.active{border-color:var(--brand);background:var(--brand-light);color:var(--brand);}
    .pm-btn:hover:not(.active){border-color:var(--brand-mid);}
    .btn-validate{background:var(--brand)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;padding:12px;font-size:.9rem;width:100%;box-shadow:0 4px 16px var(--brand-glow);transition:background .2s,transform .18s,opacity .2s;cursor:pointer;}
    .btn-validate:hover:not(:disabled){background:var(--brand-dark)!important;transform:translateY(-2px);}
    .btn-validate:disabled{opacity:.45;cursor:not-allowed;transform:none!important;}
    .btn-clear{background:var(--surface)!important;border:1.5px solid var(--border)!important;color:var(--text-muted)!important;border-radius:var(--radius-sm)!important;font-weight:600;padding:11px;font-size:.9rem;width:100%;transition:border-color .2s,color .2s;cursor:pointer;}
    .btn-clear:hover{border-color:var(--danger)!important;color:var(--danger)!important;}
    .offline-bar{background:#7c3aed;color:#fff;font-size:.8rem;font-weight:600;padding:8px 16px;border-radius:var(--radius-sm);margin-bottom:14px;display:none;align-items:center;gap:8px;}
    .modal-content{border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 20px 60px rgba(69,61,222,.18)!important;overflow:hidden;}
    .success-ring{width:72px;height:72px;background:var(--success-bg);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;animation:ringPop .4s cubic-bezier(.34,1.56,.64,1) both;}
    @keyframes ringPop{from{transform:scale(.4);opacity:0;}to{transform:scale(1);opacity:1;}}
    .success-ring i{color:var(--success);font-size:1.8rem;}
    .btn-modal-print{background:var(--brand)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;padding:9px 20px;font-size:.88rem;}
    .btn-modal-new{background:var(--surface)!important;border:1.5px solid var(--border)!important;color:var(--text-muted)!important;border-radius:var(--radius-sm)!important;font-weight:600;padding:9px 20px;font-size:.88rem;}
    .fade-up{opacity:0;transform:translateY(16px);animation:fadeUp .45s ease forwards;}
    .s1{animation-delay:.05s}.s2{animation-delay:.15s}
    @keyframes fadeUp{to{opacity:1;transform:translateY(0);}}
    .cat-filter-bar{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px;}
    .cat-filter-btn{font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:99px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);cursor:pointer;transition:all .15s;}
    .cat-filter-btn.active, .cat-filter-btn:hover{border-color:var(--brand);background:var(--brand-light);color:var(--brand);}
    .btn-brand-solid{background:var(--brand);color:#fff;border:none;border-radius:var(--radius-sm);padding:9px 20px;font-weight:700;transition:all 0.2s;}
    .btn-brand-solid:hover{background:var(--brand-dark);transform:translateY(-1px);}

    /* ── Client Search ── */
    .client-search-wrap{position:relative;}
    .client-results{position:absolute;top:100%;left:0;right:0;background:#fff;border:1.5px solid var(--border);border-top:none;border-radius:0 0 10px 10px;box-shadow:0 10px 25px rgba(0,0,0,0.1);z-index:1050;max-height:200px;overflow-y:auto;display:none;}
    .client-item{padding:8px 14px;cursor:pointer;font-size:0.84rem;transition:background 0.2s;}
    .client-item:hover{background:var(--brand-light);color:var(--brand);}
    .client-item .tel{font-size:0.75rem;color:var(--text-muted);margin-left:8px;}
</style>

<!-- Offline banner -->
<div class="offline-bar" id="offlineBar">
    <i class="fas fa-wifi-slash"></i>
    Mode hors-ligne actif — Le panier est sauvegardé localement.
    <button onclick="syncOffline()" style="margin-left:auto;background:rgba(255,255,255,.2);border:none;color:#fff;border-radius:6px;padding:3px 10px;font-size:.75rem;cursor:pointer;">Synchroniser</button>
</div>

<!-- Page header -->
<div class="d-flex align-items-center mb-3 fade-up s1">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:var(--text);letter-spacing:-.4px;margin:0;">
            Nouvelle <span style="color:var(--brand);">vente</span>
        </h1>
        <p style="color:var(--text-muted);font-size:.81rem;margin:3px 0 0;">
            <i class="fas fa-circle me-1" style="font-size:.45rem;color:var(--success);vertical-align:middle;"></i>
            Caisse active &nbsp;·&nbsp; <?= date('d/m/Y H:i') ?>
            <?php if ($sessionActive): ?>
            &nbsp;·&nbsp; <span style="color:var(--brand);font-weight:700;">Solde ouv. <?= formatPrice((float)$sessionActive['solde_ouverture']) ?></span>
            <?php endif; ?>
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
                    <span style="font-size:.74rem;color:var(--text-muted);"><?= count($produits) ?> produits</span>
                </div>
                <input type="text" id="searchProduit" class="search-input" placeholder="🔍 Rechercher un produit…">
                <!-- Filtres catégories -->
                <div class="cat-filter-bar" id="catFilterBar">
                    <button class="cat-filter-btn active" onclick="filterCat('', this)">Tout</button>
                    <?php foreach ($categories as $cat): ?>
                    <button class="cat-filter-btn" onclick="filterCat('<?= addslashes(sanitize($cat['nom'])) ?>', this)"
                            style="--c:<?= sanitize($cat['couleur']) ?>;">
                        <?= sanitize($cat['nom']) ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-body overflow-auto p-3" style="max-height:65vh">
                <?php
                $cats = [];
                foreach ($produits as $p) $cats[$p['categorie']][] = $p;
                foreach ($cats as $cat => $items):
                ?>
                <div class="cat-label cat-section" data-cat="<?= sanitize($cat) ?>"><?= sanitize($cat) ?></div>
                <div class="row g-2 cat-section" data-cat="<?= sanitize($cat) ?>">
                    <?php foreach ($items as $p):
                        $isService = stripos(sanitize($p['categorie'] ?? ''), 'service') !== false;
                        $stock = (int)($p['stock'] ?? 0);
                        if ($isService) {
                            $stockClass = 'stock-ok';
                            $stockLabel = 'Service';
                            $canAdd     = true;
                        } else {
                            $stockClass = $stock <= 0 ? 'stock-out' : ($stock < 5 ? 'stock-low' : 'stock-ok');
                            $stockLabel = $stock <= 0 ? 'Rupture' : "Stock: $stock";
                            $canAdd     = $stock > 0;
                        }
                    ?>
                    <div class="col-6 col-md-4 produit-item" data-nom="<?= sanitize($p['nom']) ?>" data-cat="<?= sanitize($p['categorie']) ?>">
                        <button class="btn-produit" <?= !$canAdd ? 'style="opacity:.5;" title="Rupture de stock"' : '' ?>
                            onclick="<?= $canAdd ? "ajouterProduit({$p['id']}, '".addslashes(sanitize($p['nom']))."', {$p['prix']}, {$stock}, ".($isService?'true':'false').")" : 'alertRupture()' ?>">
                            <?php if (!empty($p['image_url'])): ?>
                            <img src="<?= sanitize($p['image_url']) ?>" alt="" style="width:100%;height:50px;object-fit:cover;border-radius:6px;margin-bottom:6px;">
                            <?php endif; ?>
                            <div class="prod-name"><?= sanitize($p['nom']) ?></div>
                            <div class="prod-price"><?= formatPrice((float)$p['prix']) ?></div>
                            <span class="stock-badge <?= $stockClass ?>"><?= $stockLabel ?></span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Panier ── -->
    <div class="col-lg-5 fade-up s2">
        <div class="pos-card card border-0">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Panier</h6>
                <span id="cartCount" style="background:var(--brand);color:#fff;font-size:.72rem;font-weight:700;padding:2px 9px;border-radius:99px;display:none;">0</span>
            </div>
            <div class="card-body p-3">

                <!-- Client -->
                <div class="mb-3">
                    <div class="field-label d-flex justify-content-between">
                        <span>Client</span>
                        <button class="btn p-0 border-0" onclick="ouvrirModalClient()" style="color:var(--brand);font-size:0.72rem;font-weight:800;">
                            <i class="fas fa-plus-circle"></i> Nouveau client
                        </button>
                    </div>
                    <div class="client-search-wrap">
                        <input type="text" id="clientSearch" class="pos-input" value="Client comptoir" placeholder="Rechercher par nom ou téléphone…" autocomplete="off">
                        <input type="hidden" id="clientId" value="">
                        <div id="clientResults" class="client-results"></div>
                    </div>
                </div>

                <!-- Empty state -->
                <div id="panierVide">
                    <div class="cart-empty-icon"><i class="fas fa-shopping-basket"></i></div>
                    <div style="font-weight:700;color:var(--text);font-size:.88rem;">Panier vide</div>
                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:4px;">Sélectionnez des produits dans le catalogue.</div>
                </div>

                <!-- Lines -->
                <div id="panierLignes"></div>

                <hr class="cart-divider">

                <!-- Remise -->
                <div class="mb-2" id="remiseSection" style="display:none;">
                    <div class="field-label">Remise (HTG)</div>
                    <input type="number" id="remise" class="pos-input" value="0" min="0" step="0.01" oninput="render()">
                </div>

                <!-- Total -->
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span id="totalDisplay">0,00 HTG</span>
                </div>

                <!-- Paiement -->
                <div class="payment-section" id="paymentSection" style="display:none;">
                    <div class="field-label">Mode de paiement</div>
                    <div class="d-flex gap-2 mb-3">
                        <button class="pm-btn active" id="pm-especes" onclick="selectPm('especes')"><i class="fas fa-money-bill-wave d-block mb-1"></i>Espèces</button>
                        <button class="pm-btn" id="pm-credit" onclick="selectPm('credit')"><i class="fas fa-hand-holding-usd d-block mb-1"></i>Crédit</button>
                        <button class="pm-btn" id="pm-mobile" onclick="selectPm('mobile')"><i class="fas fa-mobile-alt d-block mb-1"></i>Mobile</button>
                    </div>
                    <div id="especesField">
                        <div class="field-label">Montant reçu (HTG)</div>
                        <input type="number" id="amountPaid" class="pos-input" min="0" step="0.01" placeholder="0.00" oninput="calcRendu()">
                    </div>
                    <!-- Rendu de monnaie -->
                    <div class="rendu-row mt-2" id="renduRow" style="display:none;">
                        <span style="font-size:.8rem;font-weight:700;color:var(--success);">Rendu monnaie</span>
                        <span id="renduDisplay" style="font-size:1.1rem;font-weight:900;color:var(--success);">0,00 HTG</span>
                    </div>
                </div>

                <!-- Actions -->
                <button class="btn-validate mb-2" onclick="validerCommande()" id="btnValider" disabled>
                    <i class="fas fa-check me-2"></i>Valider la commande
                </button>
                <button class="btn-clear" onclick="viderPanier()">
                    <i class="fas fa-trash me-2"></i>Vider le panier
                </button>
                <button class="btn-clear mt-2" onclick="toggleRemise()" style="font-size:.8rem;padding:8px;">
                    <i class="fas fa-tag me-1"></i>Appliquer une remise
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal succès -->
<div class="modal fade" id="modalTicket" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title"><i class="fas fa-check-circle me-2 text-success"></i>Commande validée</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="success-ring"><i class="fas fa-check"></i></div>
                <div style="font-size:1.1rem;font-weight:800;color:var(--brand);">Commande <span id="modalCmdId"></span></div>
                <p style="color:var(--text-muted);font-size:.84rem;margin:6px 0 0;">créée avec succès !</p>
                <div id="renduModal" style="background:var(--success-bg);border-radius:10px;padding:10px;margin-top:12px;display:none;">
                    <span style="font-size:.8rem;font-weight:700;color:var(--success);">Rendu monnaie :</span>
                    <span id="renduModalVal" style="font-size:1.15rem;font-weight:900;color:var(--success);margin-left:8px;"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button class="btn-modal-print btn" onclick="imprimerTicket()">
                    <i class="fas fa-print me-1"></i>Imprimer
                </button>
                <button class="btn-modal-new btn" data-bs-dismiss="modal" onclick="reinitialiser()">
                    Nouvelle vente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Client -->
<div class="modal fade" id="modalClient" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title"><i class="fas fa-user-plus me-2 text-brand"></i>Nouveau client</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="field-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" id="newClientNom" class="pos-input" placeholder="Ex: Jean Dupont">
                    </div>
                    <div class="col-md-6">
                        <label class="field-label">Téléphone</label>
                        <input type="text" id="newClientTel" class="pos-input" placeholder="Ex: 509 1234-5678">
                    </div>
                    <div class="col-md-6">
                        <label class="field-label">Email</label>
                        <input type="email" id="newClientEmail" class="pos-input" placeholder="Ex: jean@mail.com">
                    </div>
                    <div class="col-12">
                        <label class="field-label">Adresse</label>
                        <input type="text" id="newClientAdr" class="pos-input" placeholder="Adresse complète">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button class="btn-modal-new btn" data-bs-dismiss="modal">Annuler</button>
                <button class="btn-brand-solid btn" onclick="sauvegarderClient()" id="btnSaveClient">
                    <i class="fas fa-save me-1"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$extraJs = <<<'JS'
let panier   = [];
let derniereCmdId   = null;
let dernierRendu    = 0;
let modePayment     = 'especes';
let showRemise      = false;

// ── Mode hors-ligne ──────────────────────────────────────
function checkOnline() {
    const bar = document.getElementById('offlineBar');
    if (!navigator.onLine) {
        bar.style.display = 'flex';
        sauvLocal();
    } else {
        bar.style.display = 'none';
    }
}
window.addEventListener('online',  checkOnline);
window.addEventListener('offline', checkOnline);
checkOnline();

function sauvLocal() {
    const data = {
        client: document.getElementById('clientSearch').value,
        clientId: document.getElementById('clientId').value,
        panier,
        ts: Date.now()
    };
    localStorage.setItem('pos_offline_panier', JSON.stringify(data));
}

function chargerLocal() {
    const raw = localStorage.getItem('pos_offline_panier');
    if (!raw) return;
    try {
        const data = JSON.parse(raw);
        const age  = (Date.now() - data.ts) / 1000 / 60; // minutes
        if (age < 120 && data.panier && data.panier.length) {
            if (confirm(`Un panier hors-ligne de ${data.panier.length} article(s) a été trouvé. Le restaurer ?`)) {
                panier = data.panier;
                document.getElementById('clientSearch').value = data.client || 'Client comptoir';
                document.getElementById('clientId').value = data.clientId || '';
                render();
            }
        }
    } catch(e) {}
}

async function syncOffline() {
    if (navigator.onLine && panier.length) {
        await validerCommande();
        localStorage.removeItem('pos_offline_panier');
    }
}

// Init: charger panier local si dispo
window.addEventListener('DOMContentLoaded', () => {
    chargerLocal();
});

// ── Client Search & Create ──────────────────────────────
const clientInput   = document.getElementById('clientSearch');
const clientResults = document.getElementById('clientResults');
const modalClient   = new bootstrap.Modal(document.getElementById('modalClient'));

clientInput.addEventListener('input', function() {
    const q = this.value.trim();
    if (q.length < 1) {
        clientResults.style.display = 'none';
        document.getElementById('clientId').value = '';
        return;
    }
    
    // Si on efface ce qui a été sélectionné, on reset clientId
    if (document.getElementById('clientId').value) {
        document.getElementById('clientId').value = '';
    }

    clearTimeout(this.timeout);
    this.timeout = setTimeout(async () => {
        try {
            const res  = await fetch(`index.php?page=clients&action=search&q=${encodeURIComponent(q)}`);
            const data = await res.json();
            if (data.length > 0) {
                let html = '';
                data.forEach(c => {
                    html += `<div class="client-item" onclick="selectClient(${c.id}, '${c.nom.replace(/'/g, "\\'")}')">
                        <i class="fas fa-user me-2"></i><strong>${c.nom}</strong>
                        ${c.telephone ? `<span class="tel"><i class="fas fa-phone-alt me-1"></i>${c.telephone}</span>` : ''}
                    </div>`;
                });
                clientResults.innerHTML = html;
                clientResults.style.display = 'block';
            } else {
                clientResults.style.display = 'none';
            }
        } catch(e) { }
    }, 300);
});

// Fermer les résultats si on clique ailleurs
document.addEventListener('click', (e) => {
    if (!clientInput.contains(e.target) && !clientResults.contains(e.target)) {
        clientResults.style.display = 'none';
    }
});

function selectClient(id, nom) {
    clientInput.value = nom;
    document.getElementById('clientId').value = id;
    clientResults.style.display = 'none';
    sauvLocal();
}

function ouvrirModalClient() {
    document.getElementById('newClientNom').value = '';
    document.getElementById('newClientTel').value = '';
    document.getElementById('newClientEmail').value = '';
    document.getElementById('newClientAdr').value = '';
    modalClient.show();
}

async function sauvegarderClient() {
    const nom  = document.getElementById('newClientNom').value.trim();
    const tel  = document.getElementById('newClientTel').value.trim();
    const email= document.getElementById('newClientEmail').value.trim();
    const adr  = document.getElementById('newClientAdr').value.trim();
    const btn  = document.getElementById('btnSaveClient');

    if (!nom) { alert('Le nom est obligatoire.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>...';

    try {
        const res = await fetch('index.php?page=clients&action=store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nom, telephone: tel, email, adresse: adr })
        });
        const data = await res.json();
        if (data.success) {
            selectClient(data.client.id, data.client.nom);
            modalClient.hide();
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch(e) {
        alert('Erreur réseau.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Enregistrer';
    }
}

// ── Catalogue filtres ──────────────────────────────────────
function filterCat(cat, btn) {
    document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.produit-item').forEach(el => {
        el.style.display = (!cat || el.dataset.cat === cat) ? '' : 'none';
    });
    document.querySelectorAll('.cat-section').forEach(el => {
        if (!cat) { el.style.display = ''; return; }
        el.style.display = (el.dataset.cat === cat) ? '' : 'none';
    });
}

document.getElementById('searchProduit').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.produit-item').forEach(el => {
        el.style.display = el.dataset.nom.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ── Payment ───────────────────────────────────────────────
function selectPm(pm) {
    modePayment = pm;
    document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('pm-' + pm).classList.add('active');
    document.getElementById('especesField').style.display = pm === 'especes' ? '' : 'none';
    if (pm !== 'especes') document.getElementById('renduRow').style.display = 'none';
    calcRendu();
}

function calcRendu() {
    if (modePayment !== 'especes') return;
    const paid  = parseFloat(document.getElementById('amountPaid').value) || 0;
    const total = panier.reduce((s, p) => s + p.quantite * p.prix_unitaire, 0)
                  - (parseFloat(document.getElementById('remise')?.value) || 0);
    const rendu = Math.max(0, paid - total);
    const row   = document.getElementById('renduRow');
    row.style.display = paid > 0 ? 'flex' : 'none';
    document.getElementById('renduDisplay').textContent = htg(rendu);
    document.getElementById('amountPaid').style.borderColor =
        paid >= total && paid > 0 ? 'var(--success)' : 'var(--border)';
}

// ── Panier ────────────────────────────────────────────────
function alertRupture() { alert('Ce produit est en rupture de stock.'); }

function ajouterProduit(id, nom, prix, stock, isService=false) {
    const ex = panier.find(p => p.id === id);
    if (ex) {
        if (!isService && stock > 0 && ex.quantite >= stock) { alert(`Stock insuffisant (max: ${stock}).`); return; }
        ex.quantite++;
    } else {
        panier.push({ id, produit_nom: nom, quantite: 1, prix_unitaire: prix, stock, isService });
    }
    render();
    sauvLocal();
}

function changerQte(id, delta) {
    const item = panier.find(p => p.id === id);
    if (!item) return;
    const newQte = item.quantite + delta;
    if (!item.isService && newQte > (item.stock || 9999)) { alert(`Stock insuffisant (max: ${item.stock}).`); return; }
    item.quantite = newQte;
    if (item.quantite <= 0) panier = panier.filter(p => p.id !== id);
    render(); sauvLocal();
}

function setQte(id, val) {
    const item = panier.find(p => p.id === id);
    if (!item) return;
    let newQte = parseInt(val);
    if (isNaN(newQte) || newQte <= 0) newQte = 1;
    if (!item.isService && newQte > (item.stock || 9999)) { 
        alert(`Stock insuffisant (max: ${item.stock}).`); 
        newQte = item.stock;
    }
    item.quantite = Math.max(1, newQte);
    render(); sauvLocal();
}

function toggleRemise() {
    showRemise = !showRemise;
    document.getElementById('remiseSection').style.display = showRemise ? '' : 'none';
    if (!showRemise) document.getElementById('remise').value = 0;
    render();
}

function viderPanier() {
    panier = [];
    render();
    localStorage.removeItem('pos_offline_panier');
}

function render() {
    const vide    = document.getElementById('panierVide');
    const cont    = document.getElementById('panierLignes');
    const btn     = document.getElementById('btnValider');
    const badge   = document.getElementById('cartCount');
    const payEl   = document.getElementById('paymentSection');
    const remise  = parseFloat(document.getElementById('remise')?.value) || 0;

    if (!panier.length) {
        vide.style.display = 'block'; cont.innerHTML = '';
        document.getElementById('totalDisplay').textContent = '0,00 HTG';
        btn.disabled = true; badge.style.display = 'none';
        payEl.style.display = 'none';
        return;
    }
    vide.style.display = 'none'; btn.disabled = false;
    badge.style.display = 'inline';
    badge.textContent   = panier.reduce((s, p) => s + p.quantite, 0);
    payEl.style.display = '';

    let subtotal = 0, html = '';
    panier.forEach(item => {
        const sous = item.quantite * item.prix_unitaire;
        subtotal  += sous;
        html += `<div class="panier-ligne">
            <div class="line-name">${item.produit_nom}</div>
            <div class="qty-wrap">
                <button class="btn-qty" onclick="changerQte(${item.id},-1)">−</button>
                <input type="number" value="${item.quantite}" min="1" step="1" 
                       onchange="setQte(${item.id}, this.value)" 
                       style="width: 46px; text-align: center; border: 1.5px solid var(--border); border-radius: 6px; font-weight: 700; color: var(--text); padding: 2px;">
                <button class="btn-qty" onclick="changerQte(${item.id},1)">+</button>
            </div>
            <div class="line-subtotal">${htg(sous)}</div>
            <button class="btn-del" onclick="panier=panier.filter(p=>p.id!==${item.id});render();sauvLocal();" title="Supprimer">
                <i class="fas fa-times" style="font-size:.7rem;"></i>
            </button>
        </div>`;
    });
    cont.innerHTML = html;
    const total = Math.max(0, subtotal - remise);
    document.getElementById('totalDisplay').textContent = htg(total);
    calcRendu();
}

function htg(v) {
    return new Intl.NumberFormat('fr-HT',{style:'currency',currency:'HTG',currencyDisplay:'symbol'}).format(v);
}

// ── Validation ────────────────────────────────────────────
async function validerCommande() {
    const btn    = document.getElementById('btnValider');
    const client = document.getElementById('clientSearch').value.trim();
    const clientId = document.getElementById('clientId').value;
    const remise = parseFloat(document.getElementById('remise')?.value) || 0;
    const paid   = modePayment === 'especes' ? (parseFloat(document.getElementById('amountPaid').value) || 0) : 0;
    const total  = panier.reduce((s,p) => s + p.quantite * p.prix_unitaire, 0) - remise;

    if (!client) { alert('Veuillez saisir le nom du client.'); return; }
    if (!panier.length) { alert('Le panier est vide.'); return; }
    if (modePayment === 'especes' && paid > 0 && paid < total) {
        if (!confirm(`Le montant reçu (${htg(paid)}) est inférieur au total (${htg(total)}). Continuer quand même ?`)) return;
    }

    if (!navigator.onLine) {
        sauvLocal();
        alert('Vous êtes hors-ligne. Le panier a été sauvegardé. Synchronisez dès que possible.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validation…';

    try {
        const res  = await fetch('index.php?page=commande&action=store', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({
                client_name: client,
                client_id: clientId || null,
                lignes: panier,
                payment_method: modePayment,
                amount_paid: paid,
                discount: remise
            })
        });
        const data = await res.json();
        if (data.success) {
            derniereCmdId = data.commande.id;
            dernierRendu  = data.rendu || 0;
            document.getElementById('modalCmdId').textContent = '#' + derniereCmdId;
            const renduModal = document.getElementById('renduModal');
            if (dernierRendu > 0.005) {
                renduModal.style.display = '';
                document.getElementById('renduModalVal').textContent = htg(dernierRendu);
            } else {
                renduModal.style.display = 'none';
            }
            new bootstrap.Modal(document.getElementById('modalTicket')).show();
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
            localStorage.removeItem('pos_offline_panier');
        } else {
            alert('Erreur : ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
        }
    } catch(e) {
        if (!navigator.onLine) {
            sauvLocal();
            alert('Connexion perdue. Panier sauvegardé localement.');
        } else {
            alert('Erreur réseau. Réessayez.');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Valider la commande';
    }
}

function imprimerTicket() {
    if (derniereCmdId) window.open('index.php?page=commande&action=ticket&id=' + derniereCmdId, '_blank');
}

function reinitialiser() {
    panier = []; derniereCmdId = null; dernierRendu = 0;
    document.getElementById('clientSearch').value = 'Client comptoir';
    document.getElementById('clientId').value = '';
    document.getElementById('amountPaid') && (document.getElementById('amountPaid').value = '');
    render();
}
JS;
?>

<?php require VIEW_PATH . '/partials/footer.php'; ?>