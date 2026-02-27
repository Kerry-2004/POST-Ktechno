<?php require VIEW_PATH . '/partials/header.php'; ?>

<div class="d-flex align-items-center mb-4 fade-up s1">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--text);letter-spacing:-.5px;margin:0;">
            Gestion des <span style="color:var(--brand);">clients</span>
        </h1>
        <p style="color:var(--text-muted);font-size:.85rem;margin:4px 0 0;">
            Consultez et gérez la liste de vos clients et leurs coordonnées.
        </p>
    </div>
</div>

<div class="card border-0 shadow-sm fade-up s2" style="border-radius:16px; overflow:hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size:.72rem;letter-spacing:1px;">ID</th>
                        <th class="py-3 text-uppercase text-muted fw-bold" style="font-size:.72rem;letter-spacing:1px;">Client</th>
                        <th class="py-3 text-uppercase text-muted fw-bold" style="font-size:.72rem;letter-spacing:1px;">Contact</th>
                        <th class="py-3 text-uppercase text-muted fw-bold" style="font-size:.72rem;letter-spacing:1px;">Adresse</th>
                        <th class="py-3 text-uppercase text-muted fw-bold" style="font-size:.72rem;letter-spacing:1px;">Date d'ajout</th>
                        <th class="py-3 text-center text-uppercase text-muted fw-bold pe-4" style="font-size:.72rem;letter-spacing:1px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users-slash mb-3" style="font-size: 2rem; opacity: .3;"></i>
                                    <p class="mb-0">Aucun client trouvé.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted" style="font-size:.85rem;">#<?= $c['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3" style="width:36px; height:36px; background:var(--brand-light); color:var(--brand); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700;">
                                            <?= strtoupper(substr($c['nom'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size:.9rem;"><?= sanitize($c['nom']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:.85rem;">
                                        <?php if ($c['telephone']): ?>
                                            <div class="mb-1"><i class="fas fa-phone-alt me-2 text-muted" style="font-size:.75rem;"></i><?= sanitize($c['telephone']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($c['email']): ?>
                                            <div class="text-muted"><i class="fas fa-envelope me-2" style="font-size:.75rem;"></i><?= sanitize($c['email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:.85rem;"><?= $c['adresse'] ? sanitize($c['adresse']) : '--' ?></span>
                                </td>
                                <td class="text-muted" style="font-size:.85rem;">
                                    <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                                </td>
                                <td class="text-center pe-4">
                                    <?php if (isAdmin()): ?>
                                        <a href="index.php?page=clients&action=delete&id=<?= $c['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger border-0" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fade-up { opacity: 0; transform: translateY(15px); animation: fadeUp 0.5s ease forwards; }
    .s1 { animation-delay: 0.1s; }
    .s2 { animation-delay: 0.2s; }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    
    .table-hover tbody tr:hover {
        background-color: var(--brand-light) !important;
    }
</style>

<?php require VIEW_PATH . '/partials/footer.php'; ?>
