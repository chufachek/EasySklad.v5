<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="card shadow-sm auth-card">
        <div class="card-body">
            <h4 class="mb-3">Подтверждение входа</h4>
            <?php if (!empty($error)): ?>
                <div class="alert alert-warning"><?php echo e($error); ?></div>
            <?php endif; ?>
            <form method="post" action="/twofactor">
                <?php echo csrf_input(); ?>
                <div class="mb-3">
                    <label class="form-label">Код из письма</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Подтвердить</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
