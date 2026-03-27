<?php

class AuthController extends Controller
{

    public function middleware()
    {
        return [
            'login' => [],
            'logout' => [ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST, ROLE_SECRETARY]
        ];
    }



    public function login()
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!CSRF::validate($_POST['csrf_token'] ?? '')) {
                $this->view('auth.login', ['error' => 'رمز الأمان غير صالح. يرجى تحديث الصفحة.']);
                return;
            }

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $email_safe = htmlspecialchars(trim($email));
            $password_safe = htmlspecialchars(trim($password));

            if (empty($email_safe) || empty($password_safe)) {
                $this->view('auth.login', ['error' => 'Password And Email Requiered']);
                return;
            }

            //$pdo= Database::getInstance();

            if (Auth::attempt($email_safe, $password_safe)) {
                // error_log("dashborad=2");
                $this->redirect('dashboard');
            } else {
                $this->view('auth.login', ['error' => 'Login Data Is Incorrect']);
            }
        } else {
            $this->view('auth.login');
        }
    }


    public function logout()
    {
        Auth::logout();
        $this->redirect('auth/login');
    }
}
