<?php
class Warehouse
{
    public static function all($companyId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM warehouses WHERE company_id = ? ORDER BY created_at DESC');
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public static function create($companyId, $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO warehouses (company_id, name, address, created_at) VALUES (?, ?, ?, NOW())');
        return $stmt->execute(array($companyId, $data['name'], $data['address']));
    }
}
