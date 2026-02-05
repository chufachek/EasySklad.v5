<?php
class PosController
{
    public function show()
    {
        require_auth();
        require_company();
        $company = Company::findById(current_company_id());
        $warehouses = Warehouse::all(current_company_id());
        $products = Product::all(current_company_id());
        $counterparties = $this->counterparties();
        view('pos/index', array(
            'company' => $company,
            'warehouses' => $warehouses,
            'products' => $products,
            'counterparties' => $counterparties,
            'error' => flash('error'),
            'success' => flash('success'),
        ));
    }

    public function createReceipt()
    {
        $this->handleDocument('receipt');
    }

    public function createSale()
    {
        $this->handleDocument('sale');
    }

    public function createWriteoff()
    {
        $this->handleDocument('writeoff');
    }

    private function handleDocument($type)
    {
        require_auth();
        require_company();
        csrf_check();

        $warehouseId = $_POST['warehouse_id'];
        if (!$warehouseId) {
            flash('error', 'Выберите склад.');
            redirect('/pos');
        }

        $items = $this->buildItems($type);
        if (!$items) {
            flash('error', 'Добавьте позиции.');
            redirect('/pos');
        }

        $company = Company::findById(current_company_id());
        $totals = $this->calculateTotals($items, $company);

        $data = array(
            'company_id' => current_company_id(),
            'warehouse_id' => $warehouseId,
            'type' => $type,
            'status' => 'posted',
            'counterparty_id' => $_POST['counterparty_id'] ? $_POST['counterparty_id'] : null,
            'payment_type' => $type === 'sale' ? $_POST['payment_type'] : null,
            'totals_net' => $totals['net'],
            'totals_vat' => $totals['vat'],
            'totals_gross' => $totals['gross'],
            'created_by' => current_user(),
        );

        Document::create($data, $items);
        flash('success', 'Документ проведен.');
        redirect('/journal');
    }

    private function buildItems($type)
    {
        $productIds = isset($_POST['product_id']) ? $_POST['product_id'] : array();
        $qtys = isset($_POST['qty']) ? $_POST['qty'] : array();
        $prices = isset($_POST['price']) ? $_POST['price'] : array();
        $vatRates = isset($_POST['vat_rate']) ? $_POST['vat_rate'] : array();

        $items = array();
        foreach ($productIds as $index => $productId) {
            if (!$productId) {
                continue;
            }
            $qty = (float)$qtys[$index];
            $price = (float)$prices[$index];
            if ($qty <= 0) {
                continue;
            }
            $items[] = array(
                'product_id' => $productId,
                'qty' => $qty,
                'price' => $price,
                'vat_rate' => $vatRates[$index],
                'line_net' => 0,
                'line_vat' => 0,
                'line_gross' => 0,
            );
        }
        return $items;
    }

    private function calculateTotals(&$items, $company)
    {
        $net = 0;
        $vat = 0;
        $gross = 0;
        foreach ($items as &$item) {
            $rateValue = $this->vatRateValue($item['vat_rate']);
            if ($company['tax_mode'] === 'no_vat' || $item['vat_rate'] === 'none') {
                $item['line_gross'] = $item['price'] * $item['qty'];
                $item['line_net'] = $item['line_gross'];
                $item['line_vat'] = 0;
            } elseif ($company['vat_price_mode'] === 'included') {
                $item['line_gross'] = $item['price'] * $item['qty'];
                $item['line_vat'] = $item['line_gross'] * $rateValue / (1 + $rateValue);
                $item['line_net'] = $item['line_gross'] - $item['line_vat'];
            } else {
                $item['line_net'] = $item['price'] * $item['qty'];
                $item['line_vat'] = $item['line_net'] * $rateValue;
                $item['line_gross'] = $item['line_net'] + $item['line_vat'];
            }
            $net += $item['line_net'];
            $vat += $item['line_vat'];
            $gross += $item['line_gross'];
        }
        return array('net' => $net, 'vat' => $vat, 'gross' => $gross);
    }

    private function vatRateValue($vatRate)
    {
        if ($vatRate === '20') {
            return 0.20;
        }
        if ($vatRate === '10') {
            return 0.10;
        }
        if ($vatRate === '0') {
            return 0.00;
        }
        return 0;
    }

    private function counterparties()
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM counterparties WHERE company_id = ? ORDER BY name');
        $stmt->execute(array(current_company_id()));
        return $stmt->fetchAll();
    }
}
