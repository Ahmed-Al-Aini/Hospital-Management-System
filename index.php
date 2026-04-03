
<?php

// ========== إعدادات الأمان الأساسية ==========
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', __DIR__ . DS);




require_once ROOT_PATH . 'config' . DS . 'constants.php';
require_once ROOT_PATH . 'config' . DS . 'database.php';
require_once ROOT_PATH . 'config' . DS . 'app.php';
// أضف هذا السطر بعد تحميل الإعدادات
require_once ROOT_PATH . 'core' . DS . 'helpers.php';



// إعدادات عرض الأخطاء (يُفضل إيقافها في الإنتاج)
error_reporting(E_ALL);
ini_set('display_errors', 1); // اجعلها 0 في الإنتاج
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . 'storage' . DS . 'logs' . DS . 'error.log');

// ضبط المنطقة الزمنية
date_default_timezone_set('Asia/Riyadh');

// إعدادات الجلسة الآمنة
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // اجعلها 1 إذا كنت تستخدم HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 7200); // ساعتان

// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



spl_autoload_register(function ($class) {
    $direc = ['core', 'controllers', 'models'];
    foreach ($direc as $dir) {
        $file = ROOT_PATH . $dir . DS . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


try {

    $router = new Router();

    $url = $_GET['url'] ?? '';

    $router->dispatch($url);
} catch (Exception $e) {
    error_log($e->getMessage());
}

$title = "Index";

?>