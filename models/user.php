<?php
class User extends Model
{
    protected $table = 'users';

    public function getByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getWithRoles($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, GROUP_CONCAT(r.name) as roles
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id
            WHERE u.id = :id
            GROUP BY u.id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createWithRoles($data, $roles)
    {
        try {
            $this->pdo->beginTransaction();

            $userId = $this->create($data);

            foreach ($roles as $roleId) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)
                ");
                $stmt->execute(['user_id' => $userId, 'role_id' => $roleId]);
            }

            $this->pdo->commit();
            return $userId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error creating user with roles: " . $e->getMessage());
            return false;
        }
    }
}
