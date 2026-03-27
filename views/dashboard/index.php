<!-- views/dashboard/index.php -->

<div class="dashboards-container">
    <!-- رأس لوحة التحكم -->
    <div class="dashboards-header">
        <div>
            <h1>لوحة التحكم الذكية</h1>
            <p class="subtitle">متابعة التقارير والطلبات والتقييمات عالية الأولوية</p>
        </div>

        <div class="header-date">
            <i class="fas fa-calendar-alt"></i>
            <span><?php echo date('Y-m-d'); ?></span>
        </div>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $todayPrescriptions ?? 0; ?></h3>
                <p>صرف اليوم</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $pendingPrescriptions ?? 0; ?></h3>
                <p>طلبات مفتوحة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalItems ?? 0; ?></h3>
                <p>إجمالي الأصناف</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $weeklyAverage ?? '30'; ?></h3>
                <p>معدل الأسبوع الحالي</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> أثر الطلبات</h3>
        </div>
        <div class="card-body">
            <div class="impact-grid">
                <div class="impact-item">
                    <span class="impact-label">الصف:</span>
                    <span class="impact-value"><?php echo $impact_row ?? 0; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">الكمية:</span>
                    <span class="impact-value"><?php echo $impact_quantity ?? 0; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">القسم:</span>
                    <span class="impact-value"><?php echo $impact_department ?? 0; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">التصنيف:</span>
                    <span class="impact-value"><?php echo $impact_category ?? 0; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">التقييم:</span>
                    <span class="impact-value"><?php echo $impact_rating ?? 0; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- المخرجات الرئيسية -->
    <div class="outputs-grid hidden-scroll">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clipboard-list"></i> المخرجات</h3>
            </div>
            <div class="card-body">
                <ul class="outputs-list">
                    <li><a href="<?php echo BASE_URL; ?>prescription/list"><i class="fas fa-file-prescription"></i> الطلبات</a></li>
                    <li><a href="<?php echo BASE_URL; ?>pharmacy/queue"><i class="fas fa-pills"></i> الصرف</a></li>
                    <li><a href="<?php echo BASE_URL; ?>report/evaluations"><i class="fas fa-star"></i> التقييمات</a></li>
                    <li><a href="<?php echo BASE_URL; ?>report/movements"><i class="fas fa-truck"></i> حركة المحررون</a></li>
                    <li><a href="<?php echo BASE_URL; ?>report"><i class="fas fa-chart-bar"></i> التقارير</a></li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-sliders-h"></i> أسئلة الرقابة</h3>
            </div>
            <div class="card-body">
                <!-- استبدال الروابط الثابتة بروابط حقيقية -->
                <div class="control-grid">
                    <ul class="outputs-list">
                        <li> <a href="<?php echo BASE_URL; ?>control/updateEditor" class="control-item"><i class="fa fa-file-edit"></i>تحديث المحرر </a> </li>
                        <li><a href="<?php echo BASE_URL; ?>control/editEditor" class="control-item"> <i class="fa fa-edit"></i> تعديل المحرر </a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/changeActivity" class="control-item"> <i class="fa fa-exchange"></i>تغيير النشاط</a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/editSignature" class="control-item"> <i class="fa fa-pen-nib"></i>تعديل التوقيع</a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/editActions" class="control-item"> <i class="fa fa-edit"></i>تعديل الإجراءات</a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/editActions/type/1" class="control-item"> <i class="fa fa-edit"></i>تعديل الإجراءات</a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/editActions/type/2" class="control-item"> <i class="fa fa-edit"></i>تعديل الإجراءات</a></li>
                        <li><a href="<?php echo BASE_URL; ?>control/editAchievement" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنجاز</a></li>
                        <a href="<?php echo BASE_URL; ?>control/editAchievement/1" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنجاز</a>
                        <a href="<?php echo BASE_URL; ?>control/editAchievement/2" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنجاز</a>
                        <a href="<?php echo BASE_URL; ?>control/editProduction" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنتاج</a>
                        <a href="<?php echo BASE_URL; ?>control/editProduction/1" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنتاج</a>
                        <a href="<?php echo BASE_URL; ?>control/editProduction/2" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنتاج</a>
                        <a href="<?php echo BASE_URL; ?>control/editProduction/3" class="control-item"> <i class="fa fa-edit"></i>تعديل الإنتاج</a>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-tachometer-alt"></i> مؤشرات سريعة</h3>
            </div>
            <div class="card-body">
                <div class="quick-indicators">

                    <div class="indicator">
                        <span class="label">الدور الحالي:</span>

                        <div class="dropdown-center">
                            <span class="value"><?php echo implode(' || ', $_SESSION['user_roles'] ?? ['مدير النظام']); ?></span>
                        </div>
                    </div>
                    <div class="indicator">
                        <span class="label">الإدارة:</span>

                        <span class="value">نشطة</span>
                        <i class="text-success  fa fa-circle-check">
                        </i>
                    </div>
                    <div class="indicator">
                        <span class="label">مدير النظام:</span>
                        <span class="value"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'مدير'); ?></span>
                        <i class="text-success fa fa-computer">
                        </i>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .dashboards-container {
        padding: 20px;
    }

    .dashboards-header {
        margin-bottom: 30px;
    }

    .dashboards-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #000;
        margin-bottom: 5px;
    }

    .dashboards-header .subtitle {
        color: #000;
        font-size: 16px;
        background-color: #ffffffff;
    }


    .header-date {
        background: white;
        padding: 10px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        left: 0;
    }

    .header-date i {
        color: #0066cc;
    }

    .hidden-scroll {
        overflow-y: scroll;
        /* أو auto */
        scrollbar-width: none;
        /* فايرفوكس */
    }

    .hidden-scroll::-webkit-scrollbar {
        width: 0;
        height: 0;
        display: none;
    }

    .card-header i {
        color: #33475bff;
    }

    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .impact-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .impact-label {
        color: #6c757d;
        font-weight: 500;
    }

    .impact-value {
        color: #4573a1;

        font-weight: 700;
        font-size: 18px;
    }

    .outputs-grid,
    .control-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .outputs-list,
    .control-list {
        list-style: none;
        padding: 0;
    }

    .outputs-list li,
    .control-list li {
        margin-bottom: 10px;
    }

    .outputs-list a,
    .control-list a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: #f8f9fa;
        color: #000;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .outputs-list a i,
    .control-list a i {
        width: 20px;
        color: #4573a1;
    }

    .outputs-list a:hover,
    .control-list a:hover {
        background: #425567ff;
        color: white;
        transform: translateX(-5px);
    }

    .outputs-list a:hover i,
    .control-list a:hover i {
        color: white;
    }

    .control-grid {
        height: 22rem;
        overflow: auto;
        scrollbar-width: none;
    }

    .quick-indicators {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .indicator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .indicator .label {
        color: #6c757d;
        font-weight: 500;
    }

    .indicator .value {
        color: #1e2b37;
        font-weight: 600;
    }

    .made-with {
        margin-top: 30px;
        text-align: center;
        color: #6c757d;
        font-size: 12px;
        opacity: 0.7;
    }
</style>