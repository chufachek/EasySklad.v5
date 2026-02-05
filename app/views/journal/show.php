<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h4>Документ #<?php echo e($document['id']); ?></h4>
        <p class="mb-1">Тип: <strong><?php echo e($document['type']); ?></strong></p>
        <p class="mb-1">Дата: <?php echo e($document['created_at']); ?></p>
        <p class="mb-1">Склад: <?php echo e($document['warehouse_name']); ?></p>
        <p class="mb-1">Контрагент: <?php echo e($document['counterparty_name']); ?></p>
        <p>Оплата: <?php echo e($document['payment_type']); ?></p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Товар</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>НДС</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo e($item['product_name']); ?></td>
                    <td><?php echo e($item['qty']); ?></td>
                    <td><?php echo e(number_format($item['price'], 2, '.', ' ')); ?></td>
                    <td><?php echo e($item['vat_rate']); ?></td>
                    <td><?php echo e(number_format($item['line_gross'], 2, '.', ' ')); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body text-end">
        <p class="mb-1">Итого без НДС: <?php echo e(number_format($document['totals_net'], 2, '.', ' ')); ?></p>
        <p class="mb-1">НДС: <?php echo e(number_format($document['totals_vat'], 2, '.', ' ')); ?></p>
        <p class="fw-bold">Итого: <?php echo e(number_format($document['totals_gross'], 2, '.', ' ')); ?></p>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
