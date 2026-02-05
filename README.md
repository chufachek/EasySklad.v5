# EasyСклад MVP

## Быстрый старт
1. Создайте базу данных MySQL и импортируйте схему:

```bash
mysql -u root -p easysklad < migrations/schema.sql
```

2. (Опционально) Загрузите демо-данные:

```bash
mysql -u root -p easysklad < migrations/seed.sql
```

3. Скопируйте и настройте конфиг `app/config/config.php`:

- `db` — параметры MySQL
- `smtp` — параметры SMTP
- `app.base_url` — базовый URL приложения
- `app.secret_key` — секретный ключ

4. Запустите приложение через встроенный сервер PHP:

```bash
php -S localhost:8000 -t public
```

Откройте `http://localhost:8000`.

## Доступные страницы
- `/register` — регистрация
- `/login` — вход
- `/profile` — профиль
- `/companies` — список компаний
- `/warehouses` — склады
- `/products` — товары
- `/pos` — касса
- `/journal` — журнал документов

## Требования
- PHP 5.6+
- MySQL 5.7+
