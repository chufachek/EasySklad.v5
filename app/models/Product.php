<?php
class Product
{
    public static function all($companyId, $filters = array())
    {
        $pdo = db();
        $where = ' WHERE p.company_id = ? ';
        $params = array($companyId);
        if (!empty($filters['category_id'])) {
            $where .= ' AND p.category_id = ? ';
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $where .= ' AND (p.name LIKE ? OR p.sku LIKE ? OR p.barcode LIKE ?) ';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        $sql = 'SELECT p.*, c.name as category_name, IFNULL(SUM(s.qty - s.reserved_qty), 0) as stock_qty
                FROM products p
                LEFT JOIN product_categories c ON c.id = p.category_id
                LEFT JOIN stock s ON s.product_id = p.id
                ' . $where . '
                GROUP BY p.id
                ORDER BY p.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function create($companyId, $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO products (company_id, category_id, name, sku, barcode, unit, cost_price, sell_price, vat_rate, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        return $stmt->execute(array(
            $companyId,
            $data['category_id'],
            $data['name'],
            $data['sku'],
            $data['barcode'],
            $data['unit'],
            $data['cost_price'],
            $data['sell_price'],
            $data['vat_rate'],
        ));
    }

    public static function categories($companyId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM product_categories WHERE company_id = ? ORDER BY name');
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public static function createCategory($companyId, $name)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO product_categories (company_id, name, created_at) VALUES (?, ?, NOW())');
        return $stmt->execute(array($companyId, $name));
    }
}
