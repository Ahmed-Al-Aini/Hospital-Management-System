<?php
// controllers/VisitController.php

class VisitController extends Controller
{
    private $visitModel;
    private $patientModel;

    public function __construct()
    {
        $this->visitModel = new Visit();
        $this->patientModel = new Patient();
    }

    public function middleware()
    {
        return [
            'create' => [ROLE_DOCTOR],
            'store' => [ROLE_DOCTOR],
            'view' => [ROLE_DOCTOR, ROLE_ADMIN],
            'list' => [ROLE_DOCTOR, ROLE_ADMIN],
            'updateStatus' => [ROLE_DOCTOR]
        ];
    }

    /**
     * عرض نموذج إضافة زيارة
     */
    public function create()
    {
        $patientId = $_GET['patient_id'] ?? 0;

        if (!$patientId) {
            Session::flash('error', 'الرجاء اختيار مريض أولاً');
            $this->redirect('patient/list');
            return;
        }

        $patient = $this->patientModel->find($patientId);

        if (!$patient) {
            Session::flash('error', 'المريض غير موجود');
            $this->redirect('patient/list');
            return;
        }

        $this->view('visit.create', ['patient' => $patient]);
    }

    /**
     * حفظ زيارة جديدة
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient/list');
            return;
        }

        $data = [
            'patient_id' => $_POST['patient_id'] ?? 0,
            'doctor_id' => Auth::id(),
            'complaint' => $_POST['complaint'] ?? '',
            'diagnosis' => $_POST['diagnosis'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'status' => 'completed',
            'visit_date' => $_POST['visit_date'] ?? date('Y-m-d H:i:s')
        ];

        if (empty($data['patient_id'])) {
            Session::flash('error', 'بيانات غير صحيحة');
            $this->redirect('patient/list');
            return;
        }

        if ($this->visitModel->create($data)) {
            Session::flash('success', 'تم تسجيل الزيارة بنجاح');

            // إذا تم إنشاء وصفة من الزيارة
            if (isset($_POST['create_prescription']) && $_POST['create_prescription'] == 1) {
                $this->redirect('prescription/create?patient_id=' . $data['patient_id']);
            } else {
                $this->redirect('patient/views/' . $data['patient_id']);
            }
        } else {
            Session::flash('error', 'حدث خطأ أثناء تسجيل الزيارة');
            $this->redirect('visit/create?patient_id=' . $data['patient_id']);
        }
    }
    /**
     * عرض تفاصيل زيارة
     */
    public function views($id)
    {
        $visit = $this->visitModel->getWithDetails($id);

        if (!$visit) {
            Session::flash('error', 'الزيارة غير موجودة');
            $this->redirect('visit/list');
            return;
        }

        // جلب الوصفات المرتبطة بهذه الزيارة
        $prescriptionModel = new Prescription();
        $visit->prescriptions = $prescriptionModel->getByVisit($id);

        $this->view('visit.view', ['visit' => $visit]);
    }
    /**
     * عرض قائمة الزيارات
     */
    public function list()
    {
        $filter = $_GET['filter'] ?? 'all';

        switch ($filter) {
            case 'today':
                $visits = $this->visitModel->getTodayVisits();
                break;
            case 'my':
                $visits = $this->visitModel->getByDoctor(Auth::id());
                break;
            default:
                $pdo = Database::getInstance();
                $stmt = $pdo->query("
                    SELECT v.*, p.name as patient_name, u.name as doctor_name
                    FROM visits v
                    JOIN patients p ON v.patient_id = p.id
                    JOIN users u ON v.doctor_id = u.id
                    ORDER BY v.visit_date DESC
                    LIMIT 50
                ");
                $visits = $stmt->fetchAll();
        }

        $this->view('visit.list', ['visits' => $visits, 'filter' => $filter]);
    }

    /**
     * تحديث حالة الزيارة
     */
    public function updateStatus()
    {
        if (!$this->isAjax()) {
            $this->json(['error' => 'طلب غير صالح'], 400);
            return;
        }

        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';

        if ($this->visitModel->updateStatus($id, $status)) {
            $this->json(['success' => true, 'message' => 'تم تحديث الحالة']);
        } else {
            $this->json(['error' => 'فشل التحديث'], 500);
        }
    }
}
