<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="card shadow-sm auth-card">
        <div class="card-body">
            <h4 class="mb-3">Вход</h4>
            <?php if (!empty($error)): ?>
                <div class="alert alert-warning"><?php echo e($error); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['verify'])): ?>
                <form method="post" action="/verify-email/resend" class="mb-3">
                    <?php echo csrf_input(); ?>
                    <button class="btn btn-outline-success w-100">Отправить письмо подтверждения повторно</button>
                </form>
            <?php endif; ?>
            <form method="post" action="/login">
                <?php echo csrf_input(); ?>
                <div class="mb-3">
                    <label class="form-label">Email или никнейм</label>
                    <input type="text" name="identifier" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Войти</button>
            </form>
            <div class="mt-3 text-center">
                <a href="/register">Создать аккаунт</a>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
