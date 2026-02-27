<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?= str_pad($commande['id'], 5, '0', STR_PAD_LEFT) ?></title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
             font-family: 'Roboto', sans-serif;
            background: #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            gap: 15px;
        }

        /* ── Boutons écran ── */
        .controls { display: flex; gap: 10px; }
        .btn-p {
            padding: 10px 28px;
            background: #453dde;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-p:hover { background: #2d27a8; }
        .btn-c {
            padding: 10px 18px;
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        /* ── Ticket 48mm ── */
        .ticket {
            width: 44mm;           /* 48mm - 2mm marges chaque côté */
            background: #fff;
            padding: 3mm 2mm;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }

        /* ── En-tête ── */
        .tc { text-align: center; margin-bottom: 2mm; }
        .tc img {
            width: 42mm;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 1.5mm;
        }
        .cs {
            font-size: 10pt;
            color: #000;
            line-height: 1.6;
            word-break: break-word;
        }

        /* ── Séparateurs ── */
        .div  { border: none; border-top: 1px dashed #000; margin: 1.5mm 0; }
        .divs { border: none; border-top: 2px solid #000;  margin: 1.5mm 0; }

        /* ── Infos commande ── */
        /*
         * Sur 48mm avec Courier 12x24 ≈ 32 chars max par ligne
         * On utilise un layout 2 lignes par info pour éviter débordement
         */
        .info {
            width: 100%;
            font-size: 8pt;
            border-collapse: collapse;
        }
        .info td {
            padding: 0.5mm 0;
            vertical-align: top;
            line-height: 1.5;
            font-weight: bold;
        }
        .info .label { font-weight: bold; white-space: nowrap; }
        .info .value { word-break: break-word; }

        /* ── Articles ──
         * 44mm disponibles :
         * Article : 20mm | Qté : 6mm | P.U. : 9mm | Total : 9mm
         */
        .items {
            width: 100%;
            font-size: 8pt;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .items thead th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 1mm 0.5mm;
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
        }
        .items td {
            padding: 0.8mm 0.5mm;
            vertical-align: top;
            line-height: 1.4;
        }
        .items tbody tr:last-child td { padding-bottom: 1mm; }

        /* Colonnes exactes pour 44mm */
        .n  { width: 20mm; text-align: left;  word-break: break-word; overflow: hidden; }
        .q  { width: 5mm;  text-align: center; }
        .pu { width: 9mm;  text-align: right; }
        .tt { width: 10mm; text-align: right; font-weight: bold; }

        /* ── Sous-total ── */
        .subtotal {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            font-weight: bold;
            padding: 0.8mm 0;
        }

        /* ── Grand total ── */
        .grand-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11pt;
            font-weight: bold;
            padding: 1.5mm 0;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            margin-top: 2mm;
            line-height: 1.8;
        }
        .merci {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1mm;
            word-break: break-word;
        }
        .footer-sub { font-size: 7.5pt; color: #333; }
        .timestamp  { margin-top: 1mm; font-size: 7pt; color: #555; }

        /* Espace coupe papier */
        .cut-space { margin-top: 10mm; }

        /* ── Impression ── */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
                align-items: flex-start;
            }
            .controls { display: none !important; }
            .ticket {
                width: 44mm;
                box-shadow: none;
                padding: 2mm;
                margin: 0;
            }
            @page {
                size: 48mm auto;   /* ← 48mm = largeur réelle de votre imprimante */
                margin: 0;
            }
        }
    </style>
</head>
<body>

<!-- Boutons écran -->
<div class="controls">
    <button class="btn-p" onclick="window.print()">🖨️ Imprimer</button>
    <button class="btn-c" onclick="window.close()">✕ Fermer</button>
</div>

<div class="ticket">

    <!-- ── En-tête ── -->
    <div class="tc">
        <img src="public/images/logo.png"
             alt="Logo"
             onerror="this.style.display='none';">
        <div class="cs"><?= COMPANY_ADDRESS ?></div>
        <div class="cs">Tel: <?= COMPANY_PHONE ?></div>
    </div>

    <hr class="divs">

    <!-- ── Infos commande ── -->
    <table class="info">
        <tr>
            <td class="label">N° :</td>
            <td class="value">#<?= str_pad($commande['id'], 5, '0', STR_PAD_LEFT) ?></td>
        </tr>
        <tr>
            <td class="label">Client :</td>
            <td class="value"><?= sanitize($commande['client_name']) ?></td>
        </tr>
        <tr>
            <td class="label">Date :</td>
            <td class="value"><?= formatDate($commande['created_at']) ?></td>
        </tr>
        <tr>
            <td class="label">Caissier :</td>
            <td class="value"><?= sanitize($_SESSION['user_login'] ?? '-') ?></td>
        </tr>
    </table>

    <hr class="div">

    <!-- ── Articles ── -->
    <table class="items">
        <thead>
            <tr>
                <th class="n">Article</th>
                <th class="q">Qt</th>
                <th class="pu">P.U.</th>
                <th class="tt">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $nbArticles = 0;
        foreach ($commande['lignes'] as $l):
            $sous = $l['quantite'] * $l['prix_unitaire'];
            $nbArticles += $l['quantite'];
        ?>
        <tr>
            <td class="n"><?= sanitize($l['produit_nom']) ?></td>
            <td class="q"><?= $l['quantite'] ?></td>
            <td class="pu"><?= number_format($l['prix_unitaire'], 0, ',', '') ?>G</td>
            <td class="tt"><?= number_format($sous, 0, ',', '') ?>G</td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr class="div">

    <!-- Sous-total -->
    <div class="subtotal">
        <span><?= $nbArticles ?> art.</span>
        <span><?= number_format($commande['total_amount'], 0, ',', ' ') ?> HTG</span>
    </div>

    <hr class="divs">

    <!-- ── Total ── -->
    <div class="grand-total">
        <span>TOTAL</span>
        <span><?= number_format($commande['total_amount'], 0, ',', ' ') ?> HTG</span>
    </div>

    <hr class="divs">

    <!-- ── Footer ── -->
    <div class="footer">
        <div class="merci"><?= COMPANY_FOOTER ?></div>
        <div class="footer-sub">-- Conservez ce ticket --</div>
        <div class="timestamp"><?= date('d/m/Y H:i:s') ?></div>
    </div>

    <!-- Espace coupe papier -->
    <div class="cut-space"></div>

</div>

<script>
    window.addEventListener('load', () => setTimeout(() => window.print(), 300));
</script>
</body>
</html>