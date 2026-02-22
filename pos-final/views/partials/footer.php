</div><!-- /container-fluid -->

<footer class="text-center text-muted small py-3 mt-auto" style="border-top:1px solid rgba(255,255,255,.07)">
    <?= COMPANY_NAME ?> &copy; <?= date('Y') ?> &nbsp;|&nbsp; Système POS v1.0
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/app.js"></script>
<?php if (!empty($extraJs)): ?>
<script><?= $extraJs ?></script>
<?php endif; ?>
</body>
</html>
