<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Кассовый режим</h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning"><?php echo e($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo e($success); ?></div>
        <?php endif; ?>
        <ul class="nav nav-tabs" id="posTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#receipt" type="button">Приемка</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sale" type="button">Продажа</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#writeoff" type="button">Списание</button>
            </li>
        </ul>
        <div class="tab-content pt-3">
            <?php include __DIR__ . '/partials/receipt.php'; ?>
            <?php include __DIR__ . '/partials/sale.php'; ?>
            <?php include __DIR__ . '/partials/writeoff.php'; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
