<?php
// controllers/ReportController.php

class ReportController extends Controller
{
    public function middleware()
    {
        return [
            'index' => [ROLE_ADMIN],
            'consumption' => [ROLE_ADMIN],
            'inventory' => [ROLE_ADMIN],
            'prescriptions' => [ROLE_ADMIN],
            'movements' => [ROLE_ADMIN],
            'lowStock' => [ROLE_ADMIN],
            'expiry' => [ROLE_ADMIN]
        ];
    }

    public function index()
    {
        $pdo = Database::getInstance();

        // إحصائيات سريعة
        $totalConsumption = $pdo->query("SELECT COALESCE(SUM(quantity), 0) as total FROM inventory_transactions WHERE type = 'remove' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch()->total;
        $totalMedicines = $pdo->query("SELECT COUNT(*) as count FROM medicines")->fetch()->count;
        $totalPrescriptions = $pdo->query("SELECT COUNT(*) as count FROM prescriptions")->fetch()->count;

        $this->view('reports.index', [
            'totalConsumption' => $totalConsumption,
            'totalMedicines' => $totalMedicines,
            'totalPrescriptions' => $totalPrescriptions
        ]);
    }

    public function consumption()
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $type = $_GET['type'] ?? 'daily';

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT 
                m.name,
                SUM(CASE WHEN it.type = 'remove' THEN it.quantity ELSE 0 END) as total_consumed,
                COUNT(DISTINCT p.id) as prescription_count
            FROM inventory_transactions it
            JOIN medicines m ON it.medicine_id = m.id
            LEFT JOIN prescriptions p ON it.reference_id = p.id AND it.reference_type = 'prescription'
            WHERE it.type = 'remove'
                AND DATE(it.created_at) BETWEEN :start AND :end
            GROUP BY m.id
            ORDER BY total_consumed DESC
        ");
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $data = $stmt->fetchAll();

        $this->view('reports.consumption', [
            'data' => $data,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $type
        ]);
    }

    public function inventory()
    {
        $medicineModel = new Medicine();
        $medicines = $medicineModel->getAllWithStock();

        $goodStockCount = 0;
        $lowStockCount = 0;
        $criticalStockCount = 0;

        foreach ($medicines as $medicine) {
            $currentStock = $medicine->current_stock ?? 0;
            if ($currentStock <= $medicine->critical_quantity) {
                $criticalStockCount++;
            } elseif ($currentStock <= $medicine->min_quantity) {
                $lowStockCount++;
            } else {
                $goodStockCount++;
            }
        }

        $this->view('reports.inventory', [
            'medicines' => $medicines,
            'goodStockCount' => $goodStockCount,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount
        ]);
    }

    public function prescriptions()
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as count,
                SUM(CASE WHEN status = 'dispensed' THEN 1 ELSE 0 END) as dispensed
            FROM prescriptions
            GROUP BY DATE(created_at)
            ORDER BY date DESC
            LIMIT 30
        ");
        $data = $stmt->fetchAll();

        $this->view('reports.prescriptions', ['data' => $data]);
    }

    public function movements()
    {
        $transactionModel = new InventoryTransaction();
        $movements = $transactionModel->getRecent(100);
        $this->view('reports.movements', ['movements' => $movements]);
    }

    public function lowStock()
    {
        $medicineModel = new Medicine();
        $lowStock = $medicineModel->getLowStock();
        $criticalStock = $medicineModel->getCriticalStock();

        $this->view('reports.low_stock', [
            'low_stock' => $lowStock,
            'critical_stock' => $criticalStock
        ]);
    }

    public function expiry()
    {
        $medicineModel = new Medicine();
        $nearExpiry = $medicineModel->getNearExpiry(30);

        $this->view('reports.expiry', ['near_expiry' => $nearExpiry]);
    }
}
