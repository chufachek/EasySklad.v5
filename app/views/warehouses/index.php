<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Склады</h4>
    <a href="/warehouses/create" class="btn btn-success">Создать</a>
</div>
<?php if (empty($warehouses)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <p class="text-muted">Склады отсутствуют.</p>
            <a href="/warehouses/create" class="btn btn-success">Добавить склад</a>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Название</th>
                        <th>Адрес</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($warehouses as $warehouse): ?>
                    <tr>
                        <td><?php echo e($warehouse['name']); ?></td>
                        <td><?php echo e($warehouse['address']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../layout/footer.php'; ?>
