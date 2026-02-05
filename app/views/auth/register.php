<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="card shadow-sm auth-card">
        <div class="card-body">
            <h4 class="mb-3">Регистрация</h4>
            <?php if (!empty($error)): ?>
                <div class="alert alert-warning"><?php echo e($error); ?></div>
            <?php endif; ?>
            <form method="post" action="/register">
                <?php echo csrf_input(); ?>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Фамилия</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Отчество</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Никнейм</label>
                    <input type="text" name="nickname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Повтор пароля</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-success w-100">Зарегистрироваться</button>
            </form>
            <div class="mt-3 text-center">
                <a href="/login">Уже есть аккаунт</a>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
