<?php
class Company
{
    public static function create($data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO companies (owner_user_id, name, inn, business_type, currency, tax_mode, vat_default_rate, vat_price_mode, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute(array(
            $data['owner_user_id'],
            $data['name'],
            $data['inn'],
            $data['business_type'],
            $data['currency'],
            $data['tax_mode'],
            $data['vat_default_rate'],
            $data['vat_price_mode'],
        ));
        $companyId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO company_users (company_id, user_id, role) VALUES (?, ?, ?)');
        $stmt->execute(array($companyId, $data['owner_user_id'], 'owner'));
        return $companyId;
    }

    public static function forUser($userId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT c.* FROM companies c JOIN company_users cu ON cu.company_id = c.id WHERE cu.user_id = ?');
        $stmt->execute(array($userId));
        return $stmt->fetchAll();
    }

    public static function findById($companyId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM companies WHERE id = ?');
        $stmt->execute(array($companyId));
        return $stmt->fetch();
    }

    public static function updateTaxSettings($companyId, $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE companies SET tax_mode = ?, vat_default_rate = ?, vat_price_mode = ? WHERE id = ?');
        return $stmt->execute(array($data['tax_mode'], $data['vat_default_rate'], $data['vat_price_mode'], $companyId));
    }
}
