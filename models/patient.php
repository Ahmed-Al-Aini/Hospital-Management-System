<?php

class Patient extends Model
{
    protected $table = 'patients';

    public function getWithVisits($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   COUNT(v.id) as visits_count,
                   MAX(v.visit_date) as last_visit
            FROM patients p
            LEFT JOIN visits v ON p.id = v.patient_id
            WHERE p.id = :id
            GROUP BY p.id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function search($keyword)
    {
        $keyword = "%$keyword%";
        $stmt = $this->pdo->prepare("
            SELECT * FROM patients 
            WHERE name LIKE :keyword 
               OR national_id LIKE :keyword 
               OR phone LIKE :keyword
            ORDER BY name
            LIMIT 20
        ");
        $stmt->execute(['keyword' => $keyword]);
        return $stmt->fetchAll();
    }
}

?>