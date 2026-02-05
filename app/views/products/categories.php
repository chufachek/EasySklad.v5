<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4>Категории</h4>
                <form method="post" action="/categories/create" class="mb-3">
                    <?php echo csrf_input(); ?>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Название категории" required>
                        <button class="btn btn-success">Добавить</button>
                    </div>
                </form>
                <ul class="list-group">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e($category['name']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
