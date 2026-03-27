<?php
// controllers/ControlController.php

class ControlController extends Controller
{
    /**
     * تحديد صلاحيات الوصول لكل دالة
     */
    public function middleware()
    {
        return [
            'updateEditor' => [ROLE_ADMIN],
            'updateEditorPost' => [ROLE_ADMIN],
            'editEditor' => [ROLE_ADMIN],
            'editEditorPost' => [ROLE_ADMIN],
            'changeActivity' => [ROLE_ADMIN],
            'changeActivityPost' => [ROLE_ADMIN],
            'editSignature' => [ROLE_ADMIN],
            'editSignaturePost' => [ROLE_ADMIN],
            'editActions' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST],
            'editActionsPost' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST],
            'editAchievement' => [ROLE_ADMIN, ROLE_DOCTOR],
            'editAchievementPost' => [ROLE_ADMIN, ROLE_DOCTOR],
            'editProduction' => [ROLE_ADMIN, ROLE_PHARMACIST],
            'editProductionPost' => [ROLE_ADMIN, ROLE_PHARMACIST],
            'api' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST]
        ];
    }

    // ==================== تحديث المحرر ====================
    public function updateEditor()
    {
        // عرض النموذج
        $this->view('control.update-editor');
    }

    public function updateEditorPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $role = $_POST['role'] ?? 'editor';

            // التحقق من صحة البيانات
            if (empty($name) || empty($email)) {
                Session::flash('error', 'الاسم والبريد الإلكتروني مطلوبان');
                $this->redirect('control/updateEditor');
                return;
            }

            try {
                $pdo = Database::getInstance();

                // التحقق من عدم تكرار البريد
                $check = $pdo->prepare("SELECT id FROM editors WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetch()) {
                    Session::flash('error', 'البريد الإلكتروني مستخدم بالفعل');
                    $this->redirect('control/updateEditor');
                    return;
                }

                $stmt = $pdo->prepare("
                    INSERT INTO editors (name, email, phone, role, is_active) 
                    VALUES (?, ?, ?, ?, 1)
                ");
                $stmt->execute([$name, $email, $phone, $role]);

                // تسجيل النشاط
                $this->logActivity('إضافة محرر جديد', "تم إضافة المحرر: $name");

                Session::flash('success', 'تم إضافة المحرر بنجاح');
                $this->redirect('dashboard');
            } catch (PDOException $e) {
                error_log("Error adding editor: " . $e->getMessage());
                Session::flash('error', 'حدث خطأ في قاعدة البيانات');
                $this->redirect('control/updateEditor');
            }
        }
    }

    // ==================== تعديل المحرر ====================
    public function editEditor($id = null)
    {
        if (!$id) {
            $this->redirect('dashboard');
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM editors WHERE id = ?");
        $stmt->execute([$id]);
        $editor = $stmt->fetch();

        if (!$editor) {
            Session::flash('error', 'المحرر غير موجود');
            $this->redirect('dashboard');
        }

        $this->view('control.edit-editor', ['editor' => $editor]);
    }

    public function editEditorPost($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('dashboard');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (empty($name) || empty($email)) {
            Session::flash('error', 'الاسم والبريد الإلكتروني مطلوبان');
            $this->redirect('control/editEditor/' . $id);
            return;
        }

        try {
            $pdo = Database::getInstance();

            // التحقق من عدم تكرار البريد لمستخدم آخر
            $check = $pdo->prepare("SELECT id FROM editors WHERE email = ? AND id != ?");
            $check->execute([$email, $id]);
            if ($check->fetch()) {
                Session::flash('error', 'البريد الإلكتروني مستخدم بالفعل');
                $this->redirect('control/editEditor/' . $id);
                return;
            }

            $stmt = $pdo->prepare("
                UPDATE editors SET name = ?, email = ?, phone = ? WHERE id = ?
            ");
            $stmt->execute([$name, $email, $phone, $id]);

            Session::flash('success', 'تم تعديل المحرر بنجاح');
            $this->redirect('dashboard');
        } catch (PDOException $e) {
            error_log("Error updating editor: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/editEditor/' . $id);
        }
    }

    // ==================== تغيير النشاط ====================
    public function changeActivity()
    {
        $pdo = Database::getInstance();
        // جلب قائمة المحررين
        $editors = $pdo->query("SELECT id, name, is_active FROM editors")->fetchAll();
        $this->view('control.change-activity', ['editors' => $editors]);
    }

    public function changeActivityPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }

        $editorId = $_POST['editor_id'] ?? 0;
        $status = $_POST['status'] ?? 0;

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE editors SET is_active = ? WHERE id = ?");
            $stmt->execute([$status, $editorId]);

            Session::flash('success', 'تم تغيير حالة النشاط بنجاح');
            $this->redirect('dashboard');
        } catch (PDOException $e) {
            error_log("Error changing activity: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/changeActivity');
        }
    }

    // ==================== تعديل التوقيع ====================
    public function editSignature()
    {
        $pdo = Database::getInstance();
        $editors = $pdo->query("SELECT id, name, signature FROM editors")->fetchAll();
        $this->view('control.edit-signature', ['editors' => $editors]);
    }

    public function editSignaturePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }

        $editorId = $_POST['editor_id'] ?? 0;
        $signature = $_POST['signature'] ?? '';

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE editors SET signature = ? WHERE id = ?");
            $stmt->execute([$signature, $editorId]);

            Session::flash('success', 'تم تعديل التوقيع بنجاح');
            $this->redirect('dashboard');
        } catch (PDOException $e) {
            error_log("Error updating signature: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/editSignature');
        }
    }

    // ==================== تعديل الإجراءات ====================
    public function editActions($type = null, $id = null)
    {
        $pdo = Database::getInstance();
        // جلب قائمة الإجراءات
        $actions = $pdo->query("SELECT * FROM actions ORDER BY created_at DESC")->fetchAll();
        $this->view('control.edit-actions', [
            'actions' => $actions,
            'type' => $type,
            'id' => $id
        ]);
    }

    public function editActionsPost($type = null, $id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }

        $actionName = $_POST['action_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'active';

        if (empty($actionName)) {
            Session::flash('error', 'اسم الإجراء مطلوب');
            $this->redirect('control/editActions');
            return;
        }

        try {
            $pdo = Database::getInstance();

            if ($id && is_numeric($id)) {
                // تحديث إجراء موجود
                $stmt = $pdo->prepare("
                    UPDATE actions SET action_name = ?, description = ?, status = ? WHERE id = ?
                ");
                $stmt->execute([$actionName, $description, $status, $id]);
                $message = 'تم تعديل الإجراء بنجاح';
            } else {
                // إضافة إجراء جديد
                $stmt = $pdo->prepare("
                    INSERT INTO actions (action_name, description, status, created_by) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$actionName, $description, $status, Auth::id()]);
                $message = 'تم إضافة الإجراء بنجاح';
            }

            Session::flash('success', $message);
            $this->redirect('control/editActions');
        } catch (PDOException $e) {
            error_log("Error saving action: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/editActions');
        }
    }

    // ==================== تعديل الإنجاز ====================
    public function editAchievement($id = null)
    {
        $pdo = Database::getInstance();

        // جلب قائمة المستخدمين (لاختيار صاحب الإنجاز)
        $users = $pdo->query("SELECT id, name FROM users")->fetchAll();

        $achievement = null;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM achievements WHERE id = ?");
            $stmt->execute([$id]);
            $achievement = $stmt->fetch();
        }

        $this->view('control.edit-achievement', [
            'users' => $users,
            'achievement' => $achievement,
            'id' => $id
        ]);
    }

    public function editAchievementPost($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $userId = $_POST['user_id'] ?? 0;
        $achievementDate = $_POST['achievement_date'] ?? date('Y-m-d');
        $status = $_POST['status'] ?? 'pending';
        $notes = $_POST['notes'] ?? '';

        if (empty($title) || empty($userId)) {
            Session::flash('error', 'عنوان الإنجاز والمستخدم مطلوبان');
            $this->redirect('control/editAchievement/' . $id);
            return;
        }

        try {
            $pdo = Database::getInstance();

            if ($id && is_numeric($id)) {
                $stmt = $pdo->prepare("
                    UPDATE achievements 
                    SET title = ?, description = ?, user_id = ?, achievement_date = ?, status = ?, notes = ?
                    WHERE id = ?
                ");
                $stmt->execute([$title, $description, $userId, $achievementDate, $status, $notes, $id]);
                $message = 'تم تعديل الإنجاز بنجاح';
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO achievements (title, description, user_id, achievement_date, status, notes) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $description, $userId, $achievementDate, $status, $notes]);
                $message = 'تم إضافة الإنجاز بنجاح';
            }

            Session::flash('success', $message);
            $this->redirect('control/editAchievement');
        } catch (PDOException $e) {
            error_log("Error saving achievement: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/editAchievement/' . $id);
        }
    }

    // ==================== تعديل الإنتاج ====================
    public function editProduction($id = null)
    {
        $production = null;
        if ($id) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM productions WHERE id = ?");
            $stmt->execute([$id]);
            $production = $stmt->fetch();
        }

        $this->view('control.edit-production', ['production' => $production, 'id' => $id]);
    }

    public function editProductionPost($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dashboard');
        }

        $productName = $_POST['product_name'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        $unit = $_POST['unit'] ?? 'قطعة';
        $productionDate = $_POST['production_date'] ?? date('Y-m-d');
        $notes = $_POST['notes'] ?? '';

        if (empty($productName) || $quantity <= 0) {
            Session::flash('error', 'اسم المنتج والكمية مطلوبان');
            $this->redirect('control/editProduction/' . $id);
            return;
        }

        try {
            $pdo = Database::getInstance();

            if ($id && is_numeric($id)) {
                $stmt = $pdo->prepare("
                    UPDATE productions 
                    SET product_name = ?, quantity = ?, unit = ?, production_date = ?, notes = ?
                    WHERE id = ?
                ");
                $stmt->execute([$productName, $quantity, $unit, $productionDate, $notes, $id]);
                $message = 'تم تعديل الإنتاج بنجاح';
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO productions (product_name, quantity, unit, production_date, notes, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$productName, $quantity, $unit, $productionDate, $notes, Auth::id()]);
                $message = 'تم إضافة الإنتاج بنجاح';
            }

            Session::flash('success', $message);
            $this->redirect('control/editProduction');
        } catch (PDOException $e) {
            error_log("Error saving production: " . $e->getMessage());
            Session::flash('error', 'حدث خطأ في قاعدة البيانات');
            $this->redirect('control/editProduction/' . $id);
        }
    }

    // ==================== حذف إجراء (اختياري) ====================
    public function deleteAction($id)
    {
        if (!Auth::has_role(ROLE_ADMIN)) {
            $this->json(['error' => 'غير مصرح'], 403);
        }

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM actions WHERE id = ?");
            $stmt->execute([$id]);

            $this->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
        } catch (PDOException $e) {
            $this->json(['error' => 'خطأ في الحذف'], 500);
        }
    }

    // ==================== API للطلبات السريعة ====================
    public function api()
    {
        if (!$this->isAjax()) {
            $this->json(['error' => 'طلب غير صالح'], 400);
        }

        $action = $_POST['action'] ?? '';
        $id = $_POST['id'] ?? 0;
        $data = $_POST['data'] ?? [];

        switch ($action) {
            case 'quick_toggle_active':
                // تبديل حالة التفعيل لمحرر
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("UPDATE editors SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$id]);
                $this->json(['success' => true, 'message' => 'تم تغيير الحالة']);
                break;

            case 'quick_delete_action':
                // حذف إجراء سريع
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("DELETE FROM actions WHERE id = ?");
                $stmt->execute([$id]);
                $this->json(['success' => true, 'message' => 'تم الحذف']);
                break;

            default:
                $this->json(['error' => 'إجراء غير معروف']);
        }
    }

    // دالة مساعدة لتسجيل النشاطات
    private function logActivity($action, $description)
    {
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("
                INSERT INTO activity_logs (user_id, action, description, ip_address) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                Auth::id(),
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error logging activity: " . $e->getMessage());
        }
    }
}
