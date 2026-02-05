<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Журнал документов</h4>
</div>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Дата</th>
                    <th>Тип</th>
                    <th>Склад</th>
                    <th>Контрагент</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($documents as $document): ?>
                <tr onclick="window.location='/journal/<?php echo e($document['id']); ?>'" style="cursor:pointer">
                    <td><?php echo e($document['created_at']); ?></td>
                    <td><?php echo e($document['type']); ?></td>
                    <td><?php echo e($document['warehouse_name']); ?></td>
                    <td><?php echo e($document['counterparty_name']); ?></td>
                    <td><?php echo e(number_format($document['totals_gross'], 2, '.', ' ')); ?></td>
                    <td><?php echo e($document['status']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
