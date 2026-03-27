<?php
// controllers/ApiController.php

class ApiController extends Controller
{
    public function middleware()
    {
        return [
            'searchMedicines' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST],
            'checkMedicine' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST],
            'getNotifications' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST, ROLE_SECRETARY]
        ];
    }

    public function searchMedicines()
    {
        $keyword = $_GET['q'] ?? '';
        if (strlen($keyword) < 2) {
            $this->json([]);
        }

        $medicineModel = new Medicine();
        $results = $medicineModel->search($keyword);
        $this->json($results);
    }

    public function checkMedicine()
    {
        $medicineId = $_POST['medicine_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        $medicineModel = new Medicine();
        $currentStock = $medicineModel->getCurrentStock($medicineId);
        $medicine = $medicineModel->find($medicineId);

        $this->json([
            'available' => $currentStock >= $quantity,
            'current_stock' => $currentStock,
            'medicine_name' => $medicine->name ?? ''
        ]);
    }

    public function getNotifications()
    {
        // تجريبية
        $notifications = [
            ['id' => 1, 'message' => 'لديك 3 أدوية منخفضة المخزون', 'type' => 'warning'],
            ['id' => 2, 'message' => 'وصفة جديدة في الانتظار', 'type' => 'info']
        ];

        $this->json([
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }
}
