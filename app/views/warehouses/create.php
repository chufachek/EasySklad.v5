<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Создать склад</h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning"><?php echo e($error); ?></div>
        <?php endif; ?>
        <form method="post" action="/warehouses/create">
            <?php echo csrf_input(); ?>
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Адрес</label>
                <input type="text" name="address" class="form-control">
            </div>
            <button class="btn btn-success">Создать</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
