
<?php
// ========== أدوار المستخدمين ==========
define('ROLE_ADMIN', 'Admin');
define('ROLE_DOCTOR', 'Doctor');
define('ROLE_PHARMACIST', 'Pharmacist');
define('ROLE_SECRETARY', 'Secretary');
define('ROLE_PURCHASING', 'Purchasing');

// مصفوفة الأدوار
define('ROLES', [
    ROLE_ADMIN,
    ROLE_DOCTOR,
    ROLE_PHARMACIST,
    ROLE_SECRETARY,
    ROLE_PURCHASING
]);

// ========== حالات الوصفات الطبية ==========
define('STATUS_PENDING', 'pending');
define('STATUS_DISPENSED', 'dispensed');
define('STATUS_CANCELLED', 'cancelled');
define('STATUS_PARTIAL', 'partial');

// ========== حالات المخزون ==========
define('STOCK_STATUS_NORMAL', 'normal');
define('STOCK_STATUS_LOW', 'low');
define('STOCK_STATUS_CRITICAL', 'critical');
define('STOCK_STATUS_EXPIRED', 'expired');
define('STOCK_STATUS_NEAR_EXPIRY', 'near_expiry');

// ========== أنواع حركات المخزون ==========
define('TRANSACTION_ADD', 'add');
define('TRANSACTION_REMOVE', 'remove');
define('TRANSACTION_ADJUST', 'adjust');

// ========== أنواع التنبيهات ==========
define('NOTIFICATION_LOW_STOCK', 'low_stock');
define('NOTIFICATION_CRITICAL_STOCK', 'critical_stock');
define('NOTIFICATION_EXPIRY', 'expiry');
define('NOTIFICATION_NEAR_EXPIRY', 'near_expiry');

// ========== إعدادات الأمان ==========
define('BCRYPT_COST', 12);
define('SESSION_LIFETIME', 7200); // ثانية
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 دقيقة

// ========== إعدادات النظام ==========
define('PAGINATION_LIMIT', 20);
define('MIN_STOCK_ALERT_DAYS', 30); // التنبيه قبل 30 يوم من انتهاء الصلاحية
define('CRITICAL_STOCK_LEVEL', 5); // المخزون الحرج