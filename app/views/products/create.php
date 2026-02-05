<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Добавить товар</h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning"><?php echo e($error); ?></div>
        <?php endif; ?>
        <form method="post" action="/products/create">
            <?php echo csrf_input(); ?>
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Категория</label>
                    <select name="category_id" class="form-select">
                        <option value="">Без категории</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo e($category['id']); ?>"><?php echo e($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Штрихкод</label>
                    <input type="text" name="barcode" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Ед.</label>
                    <input type="text" name="unit" class="form-control" value="шт">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Себестоимость</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Цена продажи</label>
                    <input type="number" step="0.01" name="sell_price" class="form-control" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Ставка НДС</label>
                    <select name="vat_rate" class="form-select">
                        <option value="">По умолчанию</option>
                        <option value="none">Без НДС</option>
                        <option value="0">0%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-success">Сохранить</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
