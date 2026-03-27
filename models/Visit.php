<?php
// models/Visit.php

class Visit extends Model
{
    protected $table = 'visits';

    /**
     * إنشاء زيارة جديدة
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (patient_id, doctor_id, complaint, diagnosis, notes, status, visit_date) 
                VALUES 
                (:patient_id, :doctor_id, :complaint, :diagnosis, :notes, :status, :visit_date)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * جلب زيارة مع تفاصيلها
     */
    /**
     * جلب الزيارات مع تفاصيل المريض والطبيب
     */
    public function getWithDetails($id)
    {
        $stmt = $this->pdo->prepare("
        SELECT v.*, 
               p.name as patient_name,
               p.national_id as patient_national_id,
               u.name as doctor_name
        FROM {$this->table} v
        JOIN patients p ON v.patient_id = p.id
        JOIN users u ON v.doctor_id = u.id
        WHERE v.id = :id
    ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * جلب زيارات مريض معين
     */
    public function getByPatient($patientId)
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT v.*, u.name as doctor_name
            FROM {$this->table} v
            JOIN users u ON v.doctor_id = u.id
            WHERE v.patient_id = :patient_id
            ORDER BY v.visit_date DESC
        ");
            $stmt->execute(['patient_id' => $patientId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getByPatient: " . $e->getMessage());
            return [];
        }
    }
    /**
     * تحديث حالة الزيارة
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * جلب زيارات اليوم
     */
    public function getTodayVisits()
    {
        $stmt = $this->pdo->query("
            SELECT v.*, p.name as patient_name, u.name as doctor_name
            FROM {$this->table} v
            JOIN patients p ON v.patient_id = p.id
            JOIN users u ON v.doctor_id = u.id
            WHERE DATE(v.visit_date) = CURDATE()
            ORDER BY v.visit_date ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * جلب زيارات طبيب معين
     */
    public function getByDoctor($doctorId, $limit = 20)
    {
        $stmt = $this->pdo->prepare("
            SELECT v.*, p.name as patient_name,u.name as doctor_name
            FROM {$this->table} v
            JOIN patients p ON v.patient_id = p.id
             JOIN users u ON v.doctor_id = u.id
            WHERE v.doctor_id = :doctor_id
            ORDER BY v.visit_date DESC
            LIMIT :limit
        ");
        $stmt->execute(['doctor_id' => $doctorId, 'limit' => $limit]);
        return $stmt->fetchAll();
    }
}
