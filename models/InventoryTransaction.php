<?php


class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';

    /**
     * جلب آخر الحركات
     */
    public function getRecent($limit = 50)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    it.*,
                    m.name as medicine_name,
                    u.name as user_name
                FROM {$this->table} it
                LEFT JOIN medicines m ON it.medicine_id = m.id
                LEFT JOIN users u ON it.user_id = u.id
                ORDER BY it.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getRecent: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب حركات دواء معين
     */
    public function getByMedicine($medicineId, $limit = 50)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT it.*, u.name as user_name
                FROM {$this->table} it
                JOIN users u ON it.user_id = u.id
                WHERE it.medicine_id = :medicine_id
                ORDER BY it.created_at DESC
                LIMIT :limit
            ");
            $stmt->execute([
                'medicine_id' => $medicineId,
                'limit' => $limit
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getByMedicine: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب ملخص الحركات لفترة محددة
     */
    public function getSummary($startDate, $endDate)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    DATE(created_at) as date,
                    SUM(CASE WHEN type = 'add' THEN quantity ELSE 0 END) as total_added,
                    SUM(CASE WHEN type = 'remove' THEN quantity ELSE 0 END) as total_removed
                FROM {$this->table}
                WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                GROUP BY DATE(created_at)
                ORDER BY date DESC
            ");
            $stmt->execute([
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getSummary: " . $e->getMessage());
            return [];
        }
    }

    /**
     * إنشاء حركة جديدة
     */
    public function createTransaction($data)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO {$this->table} 
                    (medicine_id, user_id, type, quantity, reference_id, reference_type, expiry_date, created_at) 
                    VALUES 
                    (:medicine_id, :user_id, :type, :quantity, :reference_id, :reference_type, :expiry_date, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($data);

            $this->pdo->commit();
            return $result;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error in createTransaction: " . $e->getMessage());
            return false;
        }
    }

}
