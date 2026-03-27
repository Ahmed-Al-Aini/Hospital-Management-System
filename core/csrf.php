<?php
// core/CSRF.php

class CSRF
{
    /**
     * @return string
     */
    public static function generate()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function validate($token)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * @return string
     */
    public static function regenerate()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    /**
     * @return string
     */
    public static function field()
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * @return string
     */
    public static function metaTag()
    {
        $token = self::generate();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }

    /**
     * @return string|null
     */
    public static function getToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf_token'] ?? null;
    }

    public static function clear()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['csrf_token']);
    }

    /**
     * التحقق من التوكن في طلب POST (دالة مساعدة)
     * @return bool
     */
    public static function checkPost()
    {
        $token = $_POST['csrf_token'] ?? '';
        return self::validate($token);
    }

    /**
     * التحقق من التوكن في طلب AJAX (من الرأس)
     * @return bool
     */
    public static function checkHeader()
    {
        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return self::validate($token);
    }
}
