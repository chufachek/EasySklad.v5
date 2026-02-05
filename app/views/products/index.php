<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Товары</h4>
    <a href="/products/create" class="btn btn-success">Добавить</a>
</div>
<form class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="<?php echo e(isset($_GET['search']) ? $_GET['search'] : ''); ?>" class="form-control" placeholder="Поиск">
    </div>
    <div class="col-md-3">
        <select name="category_id" class="form-select">
            <option value="">Категория</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo e($category['id']); ?>" <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                    <?php echo e($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-success">Фильтр</button>
    </div>
</form>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>SKU</th>
                    <th>Штрихкод</th>
                    <th>Цена</th>
                    <th>Остаток</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo e($product['name']); ?></td>
                    <td><?php echo e($product['category_name']); ?></td>
                    <td><?php echo e($product['sku']); ?></td>
                    <td><?php echo e($product['barcode']); ?></td>
                    <td><?php echo e(number_format($product['sell_price'], 2, '.', ' ')); ?></td>
                    <td><?php echo e(number_format($product['stock_qty'], 3, '.', ' ')); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
