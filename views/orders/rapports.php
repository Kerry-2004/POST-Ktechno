<?php $pageTitle = 'Rapports avancés'; require VIEW_PATH . '/partials/header.php'; ?>
<style>
    :root{--brand:#453dde;--brand-dark:#2d27a8;--brand-light:#eeecfd;--brand-glow:rgba(69,61,222,.13);--success:#16a34a;--success-bg:#dcfce7;--danger:#dc2626;--danger-bg:#fee2e2;--warning:#d97706;--warning-bg:#fef3c7;--surface:#fff;--bg:#f5f4ff;--border:#e8e6fb;--text:#1a1740;--text-2:#ffffffff;--text-muted:#6b6897;--radius:16px;--radius-sm:10px;}
    body{background:var(--bg)!important;}
    .rcard{background:var(--surface);border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 4px 24px var(--brand-glow)!important;overflow:hidden;height:100%;}
    .rcard .rcard-header{background:var(--surface)!important;border-bottom:1.5px solid var(--border)!important;padding:14px 18px;font-weight:700;font-size:.9rem;color:var(--text-2);}
    .rcard .rcard-header i{color:var(--brand);}
    .stat-card{background:var(--surface);border:1.5px solid var(--border)!important;border-radius:var(--radius)!important;box-shadow:0 2px 12px var(--brand-glow)!important;transition:transform .22s ease;}
    .stat-card:hover{transform:translateY(-4px);}
    .stat-value{font-size:1.5rem;font-weight:800;color:var(--text-2);line-height:1.1;}
    .stat-label{font-size:.73rem;color:var(--text-2);font-weight:500;text-transform:uppercase;letter-spacing:.5px;margin-top:3px;}
    .stat-icon{width:50px;height:50px;border-radius:10px!important;display:flex!important;align-items:center;justify-content:center;flex-shrink:0;}
    .filter-bar{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);padding:16px 20px;margin-bottom:24px;box-shadow:0 2px 10px var(--brand-glow);}
    .filter-input{background:var(--bg)!important;border:1.5px solid var(--border)!important;border-radius:var(--radius-sm)!important;color:var(--text)!important;padding:8px 12px;font-size:.85rem;}
    .filter-input:focus{border-color:var(--brand)!important;box-shadow:0 0 0 3px rgba(69,61,222,.1)!important;}
    .btn-brand{background:var(--brand)!important;border:none!important;color:#fff!important;border-radius:var(--radius-sm)!important;font-weight:700;font-size:.85rem;padding:8px 18px;}
    .btn-brand:hover{background:var(--brand-dark)!important;}
    .btn-export{background:var(--success-bg)!important;border:1.5px solid var(--success)!important;color:var(--success)!important;border-radius:var(--radius-sm)!important;font-weight:700;font-size:.82rem;padding:7px 16px;}
    .rank-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);}
    .rank-row:last-child{border-bottom:none;}
    .rank-num{width:24px;height:24px;border-radius:50%;background:var(--brand-light);color:var(--brand);font-size:.72rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .rank-bar-wrap{flex:1;height:8px;background:var(--border);border-radius:99px;overflow:hidden;}
    .rank-bar{height:100%;background:linear-gradient(90deg,var(--brand),var(--brand-mid));border-radius:99px;}
    .pm-row{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
    .pm-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.9rem;}
    .fade-up{opacity:0;transform:translateY(18px);animation:fadeUp .5s ease forwards;}
    .s1{animation-delay:.05s}.s2{animation-delay:.12s}.s3{animation-delay:.2s}.s4{animation-delay:.28s}
    @keyframes fadeUp{to{opacity:1;transform:translateY(0);}}
</style>

<!-- Filtre -->
<div class="filter-bar fade-up s1">
    <form method="GET" action="index.php" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="rapports">
        <div class="col-md-3">
            <label class="form-label" style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-2);">Date début</label>
            <input type="date" name="date_debut" class="form-control filter-input" value="<?= htmlspecialchars($dateDebut) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-2);">Date fin</label>
            <input type="date" name="date_fin" class="form-control filter-input" value="<?= htmlspecialchars($dateFin) ?>">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-brand"><i class="fas fa-search me-2"></i>Filtrer</button>
        </div>
        <div class="col-md-auto ms-auto">
            <a href="index.php?page=rapports&action=exportCsv&date_debut=<?= urlencode($dateDebut) ?>&date_fin=<?= urlencode($dateFin) ?>"
               class="btn btn-export"><i class="fas fa-file-csv me-2"></i>Export CSV</a>
        </div>
    </form>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4 fade-up s1">
    <?php
    $statCards = [
        ['label'=>'Ventes totales',    'value'=>(int)($resume['nb_ventes']??0),                            'icon'=>'fas fa-shopping-bag',  'color'=>'var(--brand)',   'bg'=>'var(--brand-light)'],
        ['label'=>'CA total (HTG)',     'value'=>formatPrice((float)($resume['ca_total']??0)),               'icon'=>'fas fa-dollar-sign',   'color'=>'var(--success)', 'bg'=>'var(--success-bg)'],
        ['label'=>'Panier moyen',       'value'=>formatPrice((float)($resume['panier_moyen']??0)),           'icon'=>'fas fa-chart-line',    'color'=>'var(--warning)', 'bg'=>'var(--warning-bg)'],
        ['label'=>'Plus grosse vente',  'value'=>formatPrice((float)($resume['plus_grosse_vente']??0)),      'icon'=>'fas fa-trophy',        'color'=>'var(--danger)',  'bg'=>'var(--danger-bg)'],
    ];
    foreach ($statCards as $s): ?>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;"><i class="<?= $s['icon'] ?> fa-lg"></i></div>
                <div>
                    <div class="stat-value"><?= $s['value'] ?></div>
                    <div class="stat-label"><?= $s['label'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4 mb-4">

    <!-- Graphique CA par jour -->
    <div class="col-lg-8 fade-up s2">
        <div class="rcard card border-0">
            <div class="rcard-header"><i class="fas fa-chart-line me-2"></i>Chiffre d'affaires — 30 derniers jours</div>
            <div class="card-body p-3">
                <canvas id="chartCaJour" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Modes de paiement -->
    <div class="col-lg-4 fade-up s2">
        <div class="rcard card border-0">
            <div class="rcard-header"><i class="fas fa-credit-card me-2"></i>Modes de paiement</div>
            <div class="card-body p-4">
                <?php if (empty($modesPaiement)): ?>
                    <div style="color:var(--text-muted);text-align:center;padding:30px 0;">Aucune donnée</div>
                <?php else:
                    $totalPm = array_sum(array_column($modesPaiement, 'total'));
                    $pmIcons = ['especes'=>'fas fa-money-bill-wave','credit'=>'fas fa-hand-holding-usd','mobile'=>'fas fa-mobile-alt'];
                    $pmColors = ['especes'=>['var(--success-bg)','var(--success)'],'credit'=>['var(--brand-light)','var(--brand)'],'mobile'=>['var(--warning-bg)','var(--warning)']];
                    foreach ($modesPaiement as $pm):
                        $pct = $totalPm > 0 ? round((float)$pm['total']/$totalPm*100) : 0;
                        $col = $pmColors[$pm['payment_method']] ?? ['var(--brand-light)','var(--brand)'];
                        $ico = $pmIcons[$pm['payment_method']] ?? 'fas fa-coins';
                ?>
                    <div class="pm-row">
                        <div class="pm-icon" style="background:<?= $col[0] ?>;color:<?= $col[1] ?>;"><i class="<?= $ico ?>"></i></div>
                        <div style="flex:1;">
                            <div style="font-size:.82rem;font-weight:700;color:var(--text-2);"><?= ucfirst(sanitize($pm['payment_method'])) ?></div>
                            <div style="font-size:.74rem;color:var(--text-2);opacity:0.8;"><?= $pm['nb'] ?> vente(s) · <?= $pct ?>%</div>
                        </div>
                        <div style="font-weight:800;color:<?= $col[1] ?>;font-size:.85rem;"><?= formatPrice((float)$pm['total']) ?></div>
                    </div>
                <?php endforeach; endif; ?>
                <canvas id="chartPm" height="130" class="mt-3"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Top produits -->
    <div class="col-lg-6 fade-up s3">
        <div class="rcard card border-0">
            <div class="rcard-header"><i class="fas fa-trophy me-2"></i>Top 10 produits vendus</div>
            <div class="card-body p-4">
                <?php if (empty($topProduits)): ?>
                    <div style="color:var(--text-muted);text-align:center;padding:30px 0;">Aucune donnée</div>
                <?php else:
                    $maxQte = max(array_column($topProduits, 'total_qte')) ?: 1;
                    foreach ($topProduits as $i => $tp): ?>
                    <div class="rank-row">
                        <div class="rank-num"><?= $i+1 ?></div>
                        <div style="flex:2;min-width:0;">
                            <div style="font-size:.83rem;font-weight:700;color:var(--text-2);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= sanitize($tp['produit_nom']) ?></div>
                            <div class="rank-bar-wrap mt-1">
                                <div class="rank-bar" style="width:<?= round($tp['total_qte']/$maxQte*100) ?>%"></div>
                            </div>
                        </div>
                        <div style="text-align:right;min-width:80px;">
                            <div style="font-size:.82rem;font-weight:800;color:var(--brand);"><?= $tp['total_qte'] ?> unités</div>
                            <div style="font-size:.75rem;color:var(--text-2);opacity:0.8;"><?= formatPrice((float)$tp['total_ca']) ?></div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <!-- Ventes par caissier -->
    <div class="col-lg-6 fade-up s3">
        <div class="rcard card border-0">
            <div class="rcard-header"><i class="fas fa-users me-2"></i>Performance par caissier</div>
            <div class="card-body p-4">
                <?php if (empty($parCaissier)): ?>
                    <div style="color:var(--text-muted);text-align:center;padding:30px 0;">Aucune donnée</div>
                <?php else:
                    $maxCa = max(array_column($parCaissier, 'ca_total')) ?: 1;
                    foreach ($parCaissier as $pc): ?>
                    <div class="rank-row">
                        <div style="width:36px;height:36px;background:var(--brand);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.78rem;flex-shrink:0;">
                            <?= strtoupper(substr($pc['caissier'] ?? 'N', 0, 2)) ?>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.85rem;font-weight:700;color:var(--text-2);"><?= sanitize($pc['caissier'] ?? 'Inconnu') ?></div>
                            <div class="rank-bar-wrap mt-1">
                                <div class="rank-bar" style="width:<?= round($pc['ca_total']/$maxCa*100) ?>%"></div>
                            </div>
                        </div>
                        <div style="text-align:right;min-width:100px;">
                            <div style="font-size:.82rem;font-weight:800;color:var(--success);"><?= formatPrice((float)$pc['ca_total']) ?></div>
                            <div style="font-size:.75rem;color:var(--text-2);opacity:0.8;"><?= $pc['nb_ventes'] ?> vente(s)</div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- CA par catégorie -->
<div class="col-lg-12 fade-up s4 mb-4">
    <div class="rcard card border-0">
        <div class="rcard-header"><i class="fas fa-tags me-2"></i>CA par catégorie</div>
        <div class="card-body p-4">
            <canvas id="chartCategorie" height="50"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// ── Chart CA par jour ──
const caParJourLabels = <?= json_encode(array_column($caParJour, 'jour')) ?>;
const caParJourData   = <?= json_encode(array_map(fn($r)=>(float)$r['ca'], $caParJour)) ?>;

new Chart(document.getElementById('chartCaJour'), {
    type:'line',
    data:{
        labels: caParJourLabels,
        datasets:[{
            label:'CA (HTG)',
            data: caParJourData,
            borderColor:'#453dde',
            backgroundColor:'rgba(69,61,222,0.08)',
            borderWidth:2.5,
            pointRadius:4,
            pointBackgroundColor:'#453dde',
            tension:.35,
            fill:true
        }]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#e8e6fb'},ticks:{callback:v=>v.toLocaleString('fr-HT')+' G'}},x:{grid:{display:false}}}}
});

// ── Chart Modes paiement (doughnut) ──
const pmLabels = <?= json_encode(array_column($modesPaiement, 'payment_method')) ?>;
const pmData   = <?= json_encode(array_map(fn($r)=>(float)$r['total'], $modesPaiement)) ?>;
if (pmLabels.length) {
    new Chart(document.getElementById('chartPm'), {
        type:'doughnut',
        data:{labels:pmLabels, datasets:[{data:pmData, backgroundColor:['#16a34a','#453dde','#d97706'], borderWidth:0}]},
        options:{responsive:true,plugins:{legend:{position:'bottom',labels:{font:{size:11}}}}}
    });
}

// ── Chart CA par catégorie (bar) ──
const catLabels = <?= json_encode(array_column($parCategorie, 'categorie')) ?>;
const catData   = <?= json_encode(array_map(fn($r)=>(float)$r['ca'], $parCategorie)) ?>;
if (catLabels.length) {
    new Chart(document.getElementById('chartCategorie'), {
        type:'bar',
        data:{
            labels:catLabels,
            datasets:[{label:'CA (HTG)',data:catData,backgroundColor:'rgba(69,61,222,0.75)',borderRadius:8,borderSkipped:false}]
        },
        options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#e8e6fb'}},x:{grid:{display:false}}}}
    });
}
</script>

<?php require VIEW_PATH . '/partials/footer.php'; ?>
