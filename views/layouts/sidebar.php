<aside class="sidebar">
    <div class="sidebar-header">
        <h3><?php echo APP_NAME; ?></h3>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?php echo BASE_URL; ?>dashboard">
                    <i class="fas fa-home"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR, ROLE_SECRETARY])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>patient/list">
                        <i class="fas fa-users"></i>
                        <span>المرضى</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>prescription/create">
                        <i class="fas fa-prescription"></i>
                        <span>وصفة جديدة</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR, ROLE_PHARMACIST])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>prescription/list">
                        <i class="fas fa-list"></i>
                        <span>الوصفات الطبية</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>visit/list">
                        <i class="fas fa-calendar-check"></i>
                        <span>سجل الزيارات</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_PHARMACIST])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>medicine/list">
                        <i class="fas fa-pills"></i>
                        <span>الأدوية</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>pharmacy/queue">
                        <i class="fas fa-clock"></i>
                        <span>طلبات الصيدلية</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_PHARMACIST])): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>medicine/alerts">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>التنبيهات</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::has_role(ROLE_ADMIN)): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>report">
                        <i class="fas fa-chart-bar"></i>
                        <span>التقارير</span>
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="<?php echo BASE_URL; ?>auth/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<main class="main-content">