INSERT INTO users (first_name, last_name, middle_name, nickname, email, password_hash, email_verified, email_verify_token, twofa_enabled, created_at)
VALUES ('Иван', 'Иванов', NULL, 'demo', 'demo@example.com', '$2y$10$wH7oFsIuZJxg8z9x9lJp3OKXn7w1t9l6M3S0T6r9nD2RZB7jB0oEq', 1, NULL, 0, NOW());

INSERT INTO companies (owner_user_id, name, inn, business_type, currency, tax_mode, vat_default_rate, vat_price_mode, created_at)
VALUES (1, 'Демо Компания', '7700000000', 'Розничная торговля', 'RUB', 'vat', '20', 'included', NOW());

INSERT INTO company_users (company_id, user_id, role) VALUES (1, 1, 'owner');

INSERT INTO warehouses (company_id, name, address, created_at) VALUES (1, 'Основной склад', 'Москва', NOW());

INSERT INTO product_categories (company_id, name, created_at) VALUES (1, 'Напитки', NOW());
INSERT INTO product_categories (company_id, name, created_at) VALUES (1, 'Снэки', NOW());

INSERT INTO products (company_id, category_id, name, sku, barcode, unit, cost_price, sell_price, vat_rate, created_at)
VALUES
(1, 1, 'Минеральная вода', 'WTR-001', '460000000001', 'шт', 20, 35, '20', NOW()),
(1, 1, 'Сок яблочный', 'JUS-001', '460000000002', 'шт', 30, 50, '20', NOW()),
(1, 2, 'Чипсы', 'SNK-001', '460000000003', 'шт', 40, 70, '20', NOW()),
(1, 2, 'Шоколад', 'SNK-002', '460000000004', 'шт', 35, 65, '20', NOW()),
(1, 2, 'Печенье', 'SNK-003', '460000000005', 'шт', 25, 45, '20', NOW());
