<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Профиль</h5>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-warning"><?php echo e($error); ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo e($success); ?></div>
                <?php endif; ?>
                <p class="mb-1"><strong><?php echo e($user['last_name'] . ' ' . $user['first_name']); ?></strong></p>
                <p class="mb-1 text-muted">@<?php echo e($user['nickname']); ?></p>
                <p><?php echo e($user['email']); ?>
                    <?php if ($user['email_verified']): ?>
                        <span class="badge bg-success">Подтвержден</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Не подтвержден</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Безопасность</h5>
                <form method="post" action="/profile/password" class="mb-3">
                    <?php echo csrf_input(); ?>
                    <div class="mb-2">
                        <input type="password" name="current_password" class="form-control" placeholder="Текущий пароль" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="new_password" class="form-control" placeholder="Новый пароль" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Повтор" required>
                    </div>
                    <button class="btn btn-outline-success">Сменить пароль</button>
                </form>

                <form method="post" action="/profile/twofa/enable" class="d-inline">
                    <?php echo csrf_input(); ?>
                    <?php if (!$user['twofa_enabled']): ?>
                        <button class="btn btn-sm btn-success">Включить 2FA</button>
                    <?php endif; ?>
                </form>
                <form method="post" action="/profile/twofa/disable" class="d-inline">
                    <?php echo csrf_input(); ?>
                    <?php if ($user['twofa_enabled']): ?>
                        <button class="btn btn-sm btn-outline-danger">Отключить 2FA</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Налогообложение</h5>
                <?php if (!$company): ?>
                    <p class="text-muted">Выберите компанию вверху.</p>
                <?php else: ?>
                    <form method="post" action="/profile/tax-settings">
                        <?php echo csrf_input(); ?>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="tax_mode" value="vat" <?php echo $company['tax_mode'] === 'vat' ? 'checked' : ''; ?>>
                            <label class="form-check-label">Включить НДС</label>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Режим цены</label>
                            <select name="vat_price_mode" class="form-select">
                                <option value="included" <?php echo $company['vat_price_mode'] === 'included' ? 'selected' : ''; ?>>Цена включает НДС</option>
                                <option value="excluded" <?php echo $company['vat_price_mode'] === 'excluded' ? 'selected' : ''; ?>>Цена без НДС</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ставка по умолчанию</label>
                            <select name="vat_default_rate" class="form-select">
                                <option value="none" <?php echo $company['vat_default_rate'] === 'none' ? 'selected' : ''; ?>>Без НДС</option>
                                <option value="0" <?php echo $company['vat_default_rate'] === '0' ? 'selected' : ''; ?>>0%</option>
                                <option value="10" <?php echo $company['vat_default_rate'] === '10' ? 'selected' : ''; ?>>10%</option>
                                <option value="20" <?php echo $company['vat_default_rate'] === '20' ? 'selected' : ''; ?>>20%</option>
                            </select>
                        </div>
                        <button class="btn btn-success">Сохранить</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
