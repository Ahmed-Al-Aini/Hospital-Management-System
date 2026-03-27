<?php
// models/Prescription.php

class Prescription extends Model
{
    protected $table = 'prescriptions';

    /**
     * إنشاء وصفة جديدة
     */
    public function create($data)
    {
        $sql = "INSERT INTO prescriptions (patient_id, doctor_id, status, notes, created_at) 
                VALUES (:patient_id, :doctor_id, :status, :notes, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * جلب عدد وصفات اليوم
     */
    public function getTodayCount()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE DATE(created_at) = CURDATE()
            ");
            $result = $stmt->fetch();
            return $result ? (int)$result->count : 0;
        } catch (Exception $e) {
            error_log("Error in getTodayCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * جلب عدد الوصفات المنتظرة
     */
    public function getPendingCount()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE status = 'pending'
            ");
            $result = $stmt->fetch();
            return $result ? (int)$result->count : 0;
        } catch (Exception $e) {
            error_log("Error in getPendingCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * جلب الوصفات المنتظرة
     */
    public function getPending()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, 
                       pat.name as patient_name,
                       u.name as doctor_name
                FROM {$this->table} p
                JOIN patients pat ON p.patient_id = pat.id
                JOIN users u ON p.doctor_id = u.id
                WHERE p.status = 'pending'
                ORDER BY p.created_at ASC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error in getPending: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب جميع الوصفات
     */
    public function getAll()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, 
                       pat.name as patient_name,
                       u.name as doctor_name,
                       COUNT(pi.id) as items_count
                FROM {$this->table} p
                JOIN patients pat ON p.patient_id = pat.id
                JOIN users u ON p.doctor_id = u.id
                LEFT JOIN prescription_items pi ON p.id = pi.prescription_id
                GROUP BY p.id
                ORDER BY p.created_at DESC
                LIMIT 50
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error in getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب وصفة مع التفاصيل
     */
    public function getWithDetails($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.*, 
                       pat.name as patient_name,
                       pat.national_id as patient_national_id,
                       doc.name as doctor_name,
                       u.name as dispensed_by_name
                FROM {$this->table} p
                JOIN patients pat ON p.patient_id = pat.id
                JOIN users doc ON p.doctor_id = doc.id
                LEFT JOIN users u ON p.dispensed_by = u.id
                WHERE p.id = :id
            ");
            $stmt->execute(['id' => $id]);
            $prescription = $stmt->fetch();

            if ($prescription) {
                $prescription->items = $this->getItems($id);
            }

            return $prescription;
        } catch (Exception $e) {
            error_log("Error in getWithDetails: " . $e->getMessage());
            return null;
        }
    }

    /**
     * جلب عناصر الوصفة
     */
    public function getItems($prescriptionId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT pi.*, m.name as medicine_name
                FROM prescription_items pi
                JOIN medicines m ON pi.medicine_id = m.id
                WHERE pi.prescription_id = :prescription_id
            ");
            $stmt->execute(['prescription_id' => $prescriptionId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error in getItems: " . $e->getMessage());
            return [];
        }
    }

    /**
     * إضافة عنصر إلى الوصفة
     */
    public function addItem($data)
    {
        try {
            $sql = "INSERT INTO prescription_items 
                    (prescription_id, medicine_id, dosage, quantity, instructions) 
                    VALUES 
                    (:prescription_id, :medicine_id, :dosage, :quantity, :instructions)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'prescription_id' => $data['prescription_id'],
                'medicine_id' => $data['medicine_id'],
                'dosage' => $data['dosage'] ?? null,
                'quantity' => $data['quantity'] ?? 1,
                'instructions' => $data['instructions'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error in addItem: " . $e->getMessage());
            return false;
        }
    }

    /**
     * تحديث حالة الوصفة
     */
    public function updateStatus($id, $status, $dispensedBy = null)
    {
        try {
            $sql = "UPDATE {$this->table} 
                    SET status = :status, 
                        dispensed_by = :dispensed_by, 
                        dispensed_at = NOW() 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'status' => $status,
                'dispensed_by' => $dispensedBy
            ]);
        } catch (Exception $e) {
            error_log("Error in updateStatus: " . $e->getMessage());
            return false;
        }
    }
    /**
     * جلب الوصفات المرتبطة بزيارة معينة
     */
    public function getByVisit($visitId)
    {
        try {
            // التحقق من وجود عمود visit_id
            $stmt = $this->pdo->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'visit_id'");
            $stmt->execute();
            $columnExists = $stmt->fetch();

            if (!$columnExists) {
                // إذا لم يكن العمود موجوداً، نرجع مصفوفة فارغة
                error_log("Column 'visit_id' does not exist in prescriptions table");
                return [];
            }

            $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   pat.name as patient_name,
                   u.name as doctor_name
            FROM {$this->table} p
            JOIN patients pat ON p.patient_id = pat.id
            JOIN users u ON p.doctor_id = u.id
            WHERE p.visit_id = :visit_id
            ORDER BY p.created_at DESC
        ");
            $stmt->execute(['visit_id' => $visitId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getByVisit: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب الوصفات لمريض معين
     */
    public function getByPatient($patientId, $limit = 10)
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   u.name as doctor_name
            FROM {$this->table} p
            JOIN users u ON p.doctor_id = u.id
            WHERE p.patient_id = :patient_id
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");
            $stmt->execute(['patient_id' => $patientId, 'limit' => $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getByPatient: " . $e->getMessage());
            return [];
        }
    }
}
