<?php

class Model
{
    protected $pdo = null;
    protected $table = null;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }


    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");

        return $stmt->fetchAll();
    }


    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");

        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }



    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        $values = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values);";

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($data)) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }


    public function update($id, $data)
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "$column =:$column";
        }

        $sql = "UPDATE {$this->table} SET " . implode(',', $set) . " WHERE id=:id";
        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }


    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }



    public function find_where_all($condition)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $where = [];
        foreach ($condition as $column => $value) {
            $where[] = "$column =: $column";
        }
        $sql .= implode('AND', $where);

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($condition);

        return $stmt->fetchAll();
    }



    public function find_where_first($condition)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $where = [];
        foreach ($condition as $column => $value) {
            $where[] = "$column =: $column";
        }
        $sql .= implode('AND', $where) . "LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($condition);

        return $stmt->fetch();
    }


    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
