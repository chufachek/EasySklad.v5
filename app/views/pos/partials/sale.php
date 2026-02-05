<div class="tab-pane fade" id="sale">
    <form method="post" action="/pos/sale/create" class="pos-form" data-tax-mode="<?php echo e($company['tax_mode']); ?>" data-vat-mode="<?php echo e($company['vat_price_mode']); ?>">
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
                <label class="form-label">Контрагент (клиент)</label>
                <select name="counterparty_id" class="form-select counterparty-select">
                    <option value="">Обычный покупатель</option>
                    <?php foreach ($counterparties as $counterparty): ?>
                        <?php if ($counterparty['type'] === 'customer'): ?>
                            <option value="<?php echo e($counterparty['id']); ?>"><?php echo e($counterparty['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Оплата</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check pay-cash" name="payment_type" id="payCashSale" value="cash" checked>
                    <label class="btn btn-outline-success" for="payCashSale">Нал</label>
                    <input type="radio" class="btn-check" name="payment_type" id="payCashlessSale" value="cashless">
                    <label class="btn btn-outline-success" for="payCashlessSale">Безнал</label>
                    <input type="radio" class="btn-check pay-debt" name="payment_type" id="payDebtSale" value="debt" disabled>
                    <label class="btn btn-outline-success" for="payDebtSale">Долг</label>
                </div>
                <small class="text-muted">Долг доступен только при выборе контрагента.</small>
            </div>
        </div>
        <?php include __DIR__ . '/table.php'; ?>
        <div class="pos-totals"></div>
        <button class="btn btn-success mt-3">Провести продажу</button>
    </form>
</div>
