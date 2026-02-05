<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Компании</h4>
    <a href="/companies/create" class="btn btn-success">Создать</a>
</div>
<?php if (empty($companies)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <p class="text-muted">У вас пока нет компаний.</p>
            <a href="/companies/create" class="btn btn-success">Создать компанию</a>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Название</th>
                        <th>Тип бизнеса</th>
                        <th>НДС</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?php echo e($company['name']); ?></td>
                        <td><?php echo e($company['business_type']); ?></td>
                        <td><?php echo e($company['tax_mode'] === 'vat' ? 'С НДС' : 'Без НДС'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../layout/footer.php'; ?>
