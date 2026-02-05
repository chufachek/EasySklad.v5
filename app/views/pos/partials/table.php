<div class="table-responsive">
    <table class="table table-bordered align-middle pos-table">
        <thead class="table-light">
            <tr>
                <th style="width:30%">Товар</th>
                <th style="width:10%">Кол-во</th>
                <th style="width:15%">Цена</th>
                <th style="width:10%">НДС</th>
                <th style="width:15%">Сумма</th>
                <th style="width:5%"></th>
            </tr>
        </thead>
        <tbody class="pos-items">
            <tr>
                <td>
                    <select name="product_id[]" class="form-select product-select">
                        <option value="">Выберите товар</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo e($product['id']); ?>" data-sell="<?php echo e($product['sell_price']); ?>" data-cost="<?php echo e($product['cost_price']); ?>" data-vat="<?php echo e($product['vat_rate']); ?>">
                                <?php echo e($product['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" step="0.001" name="qty[]" class="form-control qty-input" value="1"></td>
                <td><input type="number" step="0.01" name="price[]" class="form-control price-input" value="0"></td>
                <td>
                    <select name="vat_rate[]" class="form-select vat-input">
                        <option value="none">Без НДС</option>
                        <option value="0">0%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                    </select>
                </td>
                <td class="line-total">0.00</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></td>
            </tr>
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-outline-success add-row">Добавить позицию</button>
