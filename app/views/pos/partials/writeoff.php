<div class="tab-pane fade" id="writeoff">
    <form method="post" action="/pos/writeoff/create" class="pos-form" data-tax-mode="<?php echo e($company['tax_mode']); ?>" data-vat-mode="<?php echo e($company['vat_price_mode']); ?>">
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
            <div class="col-md-8">
                <label class="form-label">Причина (опционально)</label>
                <input type="text" name="reason" class="form-control" placeholder="Например: порча">
            </div>
        </div>
        <?php include __DIR__ . '/table.php'; ?>
        <div class="pos-totals"></div>
        <button class="btn btn-danger mt-3">Провести списание</button>
    </form>
</div>
