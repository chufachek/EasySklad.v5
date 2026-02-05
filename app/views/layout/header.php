<?php
$companies = isset($companies) ? $companies : (current_user() ? Company::forUser(current_user()) : array());
$activeCompany = current_company_id() ? Company::findById(current_company_id()) : null;
?>
<!doctype html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyСклад</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="app-body">
<nav class="navbar navbar-expand-lg topbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/profile">EasyСклад</a>
        <div class="d-flex align-items-center gap-3">
            <?php if (current_user()): ?>
                <?php if (empty($companies)): ?>
                    <a href="/companies/create" class="btn btn-sm btn-light">Создать компанию</a>
                <?php else: ?>
                    <form method="post" action="/companies/select" class="d-flex align-items-center gap-2">
                        <?php echo csrf_input(); ?>
                        <select name="company_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Компания</option>
                            <?php foreach ($companies as $companyItem): ?>
                                <option value="<?php echo e($companyItem['id']); ?>" <?php echo $activeCompany && $activeCompany['id'] == $companyItem['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($companyItem['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
            <button class="btn btn-sm btn-outline-light" id="themeToggle">Тема</button>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <?php if (current_user()): ?>
        <aside class="col-lg-2 sidebar">
            <div class="list-group list-group-flush">
                <a class="list-group-item" href="/profile">Профиль</a>
                <a class="list-group-item" href="/companies">Компании</a>
                <a class="list-group-item" href="/warehouses">Склады</a>
                <a class="list-group-item" href="/products">Товары</a>
                <a class="list-group-item" href="/categories">Категории</a>
                <a class="list-group-item" href="/pos">Касса</a>
                <a class="list-group-item" href="/journal">Журнал</a>
                <a class="list-group-item text-danger" href="/logout">Выход</a>
            </div>
        </aside>
        <?php endif; ?>
        <main class="<?php echo current_user() ? 'col-lg-10' : 'col-12'; ?> content-area">
