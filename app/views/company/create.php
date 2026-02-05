<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Создать компанию</h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning"><?php echo e($error); ?></div>
        <?php endif; ?>
        <form method="post" action="/companies/create">
            <?php echo csrf_input(); ?>
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ИНН</label>
                <input type="text" name="inn" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Тип бизнеса</label>
                <select name="business_type" class="form-select" required>
                    <option value="Розничная торговля">Розничная торговля</option>
                    <option value="Оптовая торговля">Оптовая торговля</option>
                    <option value="Производство">Производство</option>
                    <option value="Услуги">Услуги</option>
                    <option value="Общепит">Общепит</option>
                    <option value="Интернет-магазин">Интернет-магазин</option>
                    <option value="Строительство/ремонт">Строительство/ремонт</option>
                    <option value="Логистика/доставка">Логистика/доставка</option>
                    <option value="Аптека/медицина">Аптека/медицина</option>
                    <option value="Другое">Другое</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">НДС</label>
                    <select name="tax_mode" class="form-select">
                        <option value="no_vat">Без НДС</option>
                        <option value="vat">С НДС</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Режим цены</label>
                    <select name="vat_price_mode" class="form-select">
                        <option value="included">Цена с НДС</option>
                        <option value="excluded">Цена без НДС</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ставка</label>
                    <select name="vat_default_rate" class="form-select">
                        <option value="none">Без НДС</option>
                        <option value="0">0%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-success">Создать</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
