<div class="tab-pane fade show active" id="receipt">
    <form method="post" action="/pos/receipt/create" class="pos-form" data-tax-mode="<?php echo e($company['tax_mode']); ?>" data-vat-mode="<?php echo e($company['vat_price_mode']); ?>">
        <?php echo csrf_input(); ?>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Склад</label>
                <select name="warehouse_id" class="form-select" required>
                    <option value="">Выберите склад</option>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?php echo e($warehouse['id']); ?>"><?php echo e($warehouse['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Поставщик (опционально)</label>
                <select name="counterparty_id" class="form-select">
                    <option value="">Без поставщика</option>
                    <?php foreach ($counterparties as $counterparty): ?>
                        <?php if ($counterparty['type'] === 'supplier'): ?>
                            <option value="<?php echo e($counterparty['id']); ?>"><?php echo e($counterparty['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php include __DIR__ . '/table.php'; ?>
        <div class="pos-totals"></div>
        <button class="btn btn-success mt-3">Провести приемку</button>
    </form>
</div>
