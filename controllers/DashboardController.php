<?php
// controllers/DashboardController.php

use FFI\Exception;

class DashboardController extends Controller
{
    // ✅ إضافة middleware أولاً
    public function middleware()
    {
        return [
            'index' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST, ROLE_SECRETARY],
            'stats' => [ROLE_ADMIN],
            'reports' => [ROLE_ADMIN, ROLE_DOCTOR]
        ];
    }

    // ✅ إزالة __construct والاكتفاء بـ middleware
    // (لأن Router سيتولى تنفيذ middleware قبل الوصول للدالة)

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('auth/login');
            return;
        }
        try {
            // جلب البيانات الحقيقية من قاعدة البيانات
            $prescriptionModel = new Prescription();
            $medicineModel = new Medicine();
            $impactData = $this->getImpactData();

            $data = [
                'todayPrescriptions' => $prescriptionModel->getTodayCount(),
                'pendingPrescriptions' => $prescriptionModel->getPendingCount(),
                'totalItems' => count($medicineModel->getAllWithStock()),
                'weeklyAverage' => $this->calculateWeeklyAverage(),
                'recentActivities' => $this->getRecentActivities(),
                'impact_row' => $impactData['row'],
                'impact_quantity' => $impactData['quantity'],
                'impact_department' => $impactData['department'],
                'impact_category' => $impactData['category'],
                'impact_rating' => $impactData['rating']
            ];
            $this->view('dashboard.index', $data);
        } catch (Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());

            // بيانات افتراضية في حالة الخطأ
            $this->view('dashboard.index', [
                'todayPrescriptions' => 0,
                'pendingPrescriptions' => 0,
                'totalItems' => 0,
                'weeklyAverage' => '30',
                'impact_row' => 0,
                'impact_quantity' => 0,
                'impact_department' => 0,
                'impact_category' => 0,
                'impact_rating' => 0
            ]);
        }
    }

    /**
     * ✅ جلب بيانات أثر الطلبات من قاعدة البيانات
     */
    private function getImpactData()
    {
        $pdo = Database::getInstance();

        // 1. عدد الصفوف (عدد الوصفات في اليوم)
        $row = $pdo->query("SELECT COUNT(*) as count FROM prescriptions WHERE DATE(created_at) = CURDATE()")->fetch()->count;

        // 2. الكمية (إجمالي كمية الأدوية الموصوفة اليوم)
        $quantity = $pdo->query("
            SELECT COALESCE(SUM(pi.quantity), 0) as total
            FROM prescription_items pi
            JOIN prescriptions p ON pi.prescription_id = p.id
            WHERE DATE(p.created_at) = CURDATE()
        ")->fetch()->total;

        // 3. عدد الأقسام النشطة (الأقسام التي لديها وصفات اليوم)
        $department = $pdo->query("
            SELECT COUNT(DISTINCT v.department_id) as count
            FROM visits v
            JOIN prescriptions p ON v.id = p.visit_id
            WHERE DATE(p.created_at) = CURDATE()
        ")->fetch()->count;

        // 4. التصنيف (عدد أنواع الأدوية المختلفة المستخدمة اليوم)
        $category = $pdo->query("
            SELECT COUNT(DISTINCT pi.medicine_id) as count
            FROM prescription_items pi
            JOIN prescriptions p ON pi.prescription_id = p.id
            WHERE DATE(p.created_at) = CURDATE()
        ")->fetch()->count;

        // 5. التقييم (متوسط التقييمات لليوم - إذا كان موجوداً)
        $rating = $pdo->query("
            SELECT COALESCE(AVG(e.rating), 0) as avg
            FROM evaluations e
            WHERE DATE(e.created_at) = CURDATE()
        ")->fetch()->avg;

        return [
            'row' => $row,
            'quantity' => $quantity,
            'department' => $department > 0 ? $department : 1,
            'category' => $category > 0 ? $category : 1,
            'rating' => $rating > 0 ? round($rating, 1) : 5
        ];
    }

    public function stats()
    {
        // فقط Admin يمكنه الوصول هنا
        $this->view('dashboard.stats');
    }

    private function calculateWeeklyAverage()
    {
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->query("
                SELECT COALESCE(AVG(daily_count), 30) as avg_count
                FROM (
                    SELECT COUNT(*) as daily_count
                    FROM prescriptions
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    GROUP BY DATE(created_at)
                ) as daily
            ");
            $result = $stmt->fetch();
            return round($result->avg_count);
        } catch (Exception $e) {
            error_log("Error calculating average: " . $e->getMessage());
            return '30';
        }
    }

    private function getRecentActivities()
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("
            SELECT it.*, u.name as user_name, m.name as medicine_name
            FROM inventory_transactions it
            JOIN users u ON it.user_id = u.id
            JOIN medicines m ON it.medicine_id = m.id
            ORDER BY it.created_at DESC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }
}
