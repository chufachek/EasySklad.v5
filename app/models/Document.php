<?php
class Document
{
    public static function create($data, $items)
    {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO documents (company_id, warehouse_id, type, status, counterparty_id, payment_type, totals_net, totals_vat, totals_gross, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute(array(
                $data['company_id'],
                $data['warehouse_id'],
                $data['type'],
                $data['status'],
                $data['counterparty_id'],
                $data['payment_type'],
                $data['totals_net'],
                $data['totals_vat'],
                $data['totals_gross'],
                $data['created_by'],
            ));
            $docId = $pdo->lastInsertId();
            $stmtItem = $pdo->prepare('INSERT INTO document_items (document_id, product_id, qty, price, vat_rate, line_net, line_vat, line_gross) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            foreach ($items as $item) {
                $stmtItem->execute(array(
                    $docId,
                    $item['product_id'],
                    $item['qty'],
                    $item['price'],
                    $item['vat_rate'],
                    $item['line_net'],
                    $item['line_vat'],
                    $item['line_gross'],
                ));
                self::updateStock($data['company_id'], $data['warehouse_id'], $item['product_id'], $item['qty'], $data['type']);
            }
            $pdo->commit();
            return $docId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private static function updateStock($companyId, $warehouseId, $productId, $qty, $type)
    {
        $pdo = db();
        $sign = ($type === 'receipt') ? 1 : -1;
        $stmt = $pdo->prepare('INSERT INTO stock (company_id, warehouse_id, product_id, qty, reserved_qty) VALUES (?, ?, ?, ?, 0) ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)');
        $stmt->execute(array($companyId, $warehouseId, $productId, $qty * $sign));
    }

    public static function listForCompany($companyId)
    {
        $pdo = db();
        $sql = 'SELECT d.*, w.name as warehouse_name, c.name as counterparty_name
                FROM documents d
                LEFT JOIN warehouses w ON w.id = d.warehouse_id
                LEFT JOIN counterparties c ON c.id = d.counterparty_id
                WHERE d.company_id = ?
                ORDER BY d.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public static function find($companyId, $id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT d.*, w.name as warehouse_name, c.name as counterparty_name
                FROM documents d
                LEFT JOIN warehouses w ON w.id = d.warehouse_id
                LEFT JOIN counterparties c ON c.id = d.counterparty_id
                WHERE d.company_id = ? AND d.id = ?');
        $stmt->execute(array($companyId, $id));
        return $stmt->fetch();
    }

    public static function items($documentId)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT di.*, p.name as product_name FROM document_items di JOIN products p ON p.id = di.product_id WHERE di.document_id = ?');
        $stmt->execute(array($documentId));
        return $stmt->fetchAll();
    }
}
