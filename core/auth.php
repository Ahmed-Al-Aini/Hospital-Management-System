<?php
class Auth
{
    //private static $user = null;

    public static function attempt($email, $password)
    {
        $pdo = Database::getInstance();

        if (!$pdo) {
            die("not data base");
        }
        $stmt = $pdo->prepare("SELECT *FROM users WHERE email=:email AND is_active = 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();



        if (!$user) {
            error_log($email);
        }



        if ($user && password_verify($password, $user->password)) {
            $pdo->prepare("UPDATE users SET last_login =NOW() WHERE id =:id")->execute([":id" => $user->id]);
            $rolestmt = $pdo->prepare("SELECT r.name FROM user_roles ur JOIN roles r  ON ur.role_id=r.id WHERE ur.user_id=:user_id");
            $rolestmt->execute(['user_id' => $user->id]);
            $roles = $rolestmt->fetchAll(PDO::FETCH_COLUMN);

            // foreach($roles as $r){
            //     error_log($r);
            // }

            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['organization_id'] = $user->organization_id;
            $_SESSION['user_roles'] = $roles;
            $_SESSION['login_time'] = time();


            session_regenerate_id(true);

            return true;
        }

        return false;
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }




    public static function check()
    {
        //error_log($_SESSION['user_id']);
        if (!isset($_SESSION['user_id'])) return false;

        if (time() - $_SESSION['login_time'] > SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        return true;
    }


    public static function user($pdo, $id)
    {

        if (!self::check()) {
            return null;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->execute(['id' => $_SESSION['user_id']]);

        return $stmt->fetch();
    }



    public static function has_role($role)
    {
        // return self::check() && in_array($role,[ROLE_ADMIN,ROLE_DOCTOR,ROLE_PHARMACIST,ROLE_SECRETARY]);
        return self::check() && in_array($role, $_SESSION['user_roles'] ?? []);
    }



    public static function hasAnyRole($roles)
    {
        if (!self::check()) return false;

        $userRoles= $_SESSION['user_roles'] ?? [];

        foreach ($roles as $role) {
            if (in_array($role,$userRoles)) {
                return true;
            }
        }
        return false;
    }




    public static function hasAllRole($roles)
    {
        if (!self::check()) return false;

        $userRoles = $_SESSION['user_roles'] ?? [];

        foreach ($roles as $role) {
            if (!in_array($role, $userRoles)) {
                return false;
            }
        }
        return true;
    }


    public static function can($permission)
    {
        if (!self::check()) return false;

        // Admin لديه كل الصلاحيات
        if (self::has_role(ROLE_ADMIN)) {
            return true;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count
            FROM user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE ur.user_id = ? AND p.key_name = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $permission]);
        $result = $stmt->fetch();

        return $result->count > 0;
    }




    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }



    public static function name()
    {
        return $_SESSION['user_name'] ?? null;
    }
}
