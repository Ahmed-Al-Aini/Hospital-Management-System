<!-- views/visit/view.php -->
<div class="visit-details-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>تفاصيل الزيارة</h1>
            <p class="subtitle">زيارة رقم #<?php echo $visit->id; ?> للمريض <?php echo htmlspecialchars($visit->patient_name); ?></p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>
            <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $visit->patient_id; ?>" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة للمريض
            </a>
            <a href="<?php echo BASE_URL; ?>visit/list" class="btn-back">
                <i class="fas fa-list"></i> قائمة الزيارات
            </a>
        </div>
    </div>

    <!-- بطاقات إحصائيات الزيارة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('Y-m-d', strtotime($visit->visit_date)); ?></h3>
                <p>تاريخ الزيارة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('H:i', strtotime($visit->visit_date)); ?></h3>
                <p>وقت الزيارة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="stat-details">
                <h3>د. <?php echo htmlspecialchars($visit->doctor_name); ?></h3>
                <p>الطبيب المعالج</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-<?php echo $visit->status == 'completed' ? 'check-circle' : 'clock'; ?>"></i>
            </div>
            <div class="stat-details">
                <h3>
                    <span class="badge badge-<?php echo $visit->status; ?>">
                        <?php
                        echo $visit->status == 'completed' ? 'مكتملة' : ($visit->status == 'in-progress' ? 'قيد العلاج' : ($visit->status == 'waiting' ? 'انتظار' : 'ملغية'));
                        ?>
                    </span>
                </h3>
                <p>حالة الزيارة</p>
            </div>
        </div>
    </div>

    <!-- معلومات المريض -->
    <div class="patient-summary-card">
        <div class="patient-avatar">
            <i class="fas fa-user-circle fa-3x"></i>
        </div>
        <div class="patient-info">
            <h3><?php echo htmlspecialchars($visit->patient_name); ?></h3>
            <p>
                <i class="fas fa-id-card"></i> <?php echo htmlspecialchars($visit->patient_national_id ?? 'لا يوجد'); ?> |
                <i class="fas fa-phone"></i> <?php
                                                $patientModel = new Patient();
                                                $patient = $patientModel->find($visit->patient_id);
                                                echo htmlspecialchars($patient->phone ?? 'لا يوجد');
                                                ?>
            </p>
        </div>
        <div class="patient-actions">
            <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $visit->patient_id; ?>" class="btn-action">
                <i class="fas fa-prescription"></i> وصفة
            </a>
        </div>
    </div>

    <!-- تفاصيل الزيارة -->
    <div class="details-grid">
        <!-- الشكوى -->
        <div class="detail-card">
            <div class="detail-header">
                <i class="fas fa-head-side-cough"></i>
                <h3>الشكوى الرئيسية</h3>
            </div>
            <div class="detail-body">
                <?php if ($visit->complaint): ?>
                    <p><?php echo nl2br(htmlspecialchars($visit->complaint)); ?></p>
                <?php else: ?>
                    <p class="text-muted">لا توجد شكوى مسجلة</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- التشخيص -->
        <div class="detail-card">
            <div class="detail-header">
                <i class="fas fa-stethoscope"></i>
                <h3>التشخيص</h3>
            </div>
            <div class="detail-body">
                <?php if ($visit->diagnosis): ?>
                    <p><?php echo nl2br(htmlspecialchars($visit->diagnosis)); ?></p>
                <?php else: ?>
                    <p class="text-muted">لا يوجد تشخيص مسجل</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- ملاحظات إضافية -->
        <div class="detail-card full-width">
            <div class="detail-header">
                <i class="fas fa-notes-medical"></i>
                <h3>ملاحظات إضافية</h3>
            </div>
            <div class="detail-body">
                <?php if ($visit->notes): ?>
                    <p><?php echo nl2br(htmlspecialchars($visit->notes)); ?></p>
                <?php else: ?>
                    <p class="text-muted">لا توجد ملاحظات إضافية</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- الوصفات المرتبطة بالزيارة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-prescription"></i> الوصفات الطبية المرتبطة</h3>
            <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $visit->patient_id; ?>&visit_id=<?php echo $visit->id; ?>" class="btn-add-small">
                <i class="fas fa-plus"></i> إضافة وصفة
            </a>
        </div>
        <div class="card-body">
            <?php
            // جلب الوصفات المرتبطة بهذه الزيارة
            $prescriptionModel = new Prescription();
            $prescriptions = $prescriptionModel->getByVisit($visit->id);
            ?>

            <?php if (empty($prescriptions)): ?>
                <div class="empty-state">
                    <i class="fas fa-prescription-bottle"></i>
                    <p>لا توجد وصفات طبية مرتبطة بهذه الزيارة</p>
                </div>
            <?php else: ?>
                <div class="prescriptions-list">
                    <?php foreach ($prescriptions as $presc): ?>
                        <div class="prescription-item">
                            <div class="prescription-header">
                                <span class="prescription-id">وصفة #<?php echo $presc->id; ?></span>
                                <span class="prescription-date"><?php echo date('Y-m-d', strtotime($presc->created_at)); ?></span>
                            </div>
                            <div class="prescription-body">
                                <span class="badge badge-<?php echo $presc->status == 'dispensed' ? 'success' : 'warning'; ?>">
                                    <?php echo $presc->status == 'dispensed' ? 'تم الصرف' : 'قيد الانتظار'; ?>
                                </span>
                            </div>
                            <div class="prescription-footer">
                                <a href="<?php echo BASE_URL; ?>prescription/views/<?php echo $presc->id; ?>" class="btn-view">
                                    عرض التفاصيل <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- أزرار الإجراءات السفلية -->
    <div class="action-buttons-bottom">
        <a href="<?php echo BASE_URL; ?>visit/list" class="btn-secondary">
            <i class="fas fa-list"></i> جميع الزيارات
        </a>
        <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $visit->patient_id; ?>" class="btn-primary">
            <i class="fas fa-user"></i> صفحة المريض
        </a>
        <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
            <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $visit->patient_id; ?>&visit_id=<?php echo $visit->id; ?>" class="btn-success">
                <i class="fas fa-prescription"></i> إنشاء وصفة
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
    /* ===== التصميم العام ===== */
    .visit-details-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f9fa;
    }

    /* ===== رأس الصفحة ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1e2b37;
        margin: 0 0 5px 0;
    }

    .page-header .subtitle {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .header-date {
        background: white;
        padding: 10px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .header-date i {
        color: #0066cc;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    /* ===== بطاقات الإحصائيات ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.blue {
        background: #e3f2fd;
        color: #0066cc;
    }

    .stat-icon.green {
        background: #e8f5e9;
        color: #28a745;
    }

    .stat-icon.orange {
        background: #fff3e0;
        color: #fd7e14;
    }

    .stat-icon.purple {
        background: #f3e5f5;
        color: #6f42c1;
    }

    .stat-icon i {
        font-size: 24px;
    }

    .stat-details h3 {
        font-size: 18px;
        margin: 0 0 5px 0;
        color: #1e2b37;
        font-weight: 700;
    }

    .stat-details p {
        margin: 0;
        color: #6c757d;
        font-size: 13px;
    }

    /* ===== بطاقة ملخص المريض ===== */
    .patient-summary-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .patient-avatar i {
        color: #0066cc;
    }

    .patient-info {
        flex: 1;
    }

    .patient-info h3 {
        margin: 0 0 5px 0;
        color: #1e2b37;
    }

    .patient-info p {
        margin: 0;
        color: #6c757d;
    }

    .patient-info i {
        color: #0066cc;
        margin-left: 5px;
    }

    .patient-actions .btn-action {
        background: #f8f9fa;
        color: #0066cc;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        border: 1px solid #dee2e6;
    }

    .patient-actions .btn-action:hover {
        background: #0066cc;
        color: white;
    }

    /* ===== شبكة التفاصيل ===== */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .detail-card.full-width {
        grid-column: 1 / -1;
    }

    .detail-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-header i {
        color: #0066cc;
        font-size: 18px;
    }

    .detail-header h3 {
        margin: 0;
        color: #1e2b37;
        font-size: 16px;
        font-weight: 600;
    }

    .detail-body {
        padding: 20px;
    }

    .detail-body p {
        margin: 0;
        color: #1e2b37;
        line-height: 1.6;
    }

    /* ===== البطاقة العامة ===== */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card-header {
        padding: 18px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .card-header h3 {
        margin: 0;
        color: #1e2b37;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h3 i {
        color: #0066cc;
    }

    .card-body {
        padding: 25px;
    }

    /* ===== قائمة الوصفات ===== */
    .prescriptions-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .prescription-item {
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        border-right: 4px solid #0066cc;
        transition: all 0.3s;
    }

    .prescription-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .prescription-header {
        padding: 12px 15px;
        background: white;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .prescription-id {
        font-weight: 600;
        color: #0066cc;
    }

    .prescription-date {
        color: #6c757d;
        font-size: 12px;
    }

    .prescription-body {
        padding: 15px;
        text-align: center;
    }

    .prescription-footer {
        padding: 12px 15px;
        border-top: 1px solid #dee2e6;
        text-align: left;
    }

    .btn-view {
        color: #0066cc;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-view:hover {
        text-decoration: underline;
    }

    .btn-add-small {
        background: #0066cc;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-add-small:hover {
        background: #0052a3;
    }

    /* ===== أزرار الإجراءات السفلية ===== */
    .action-buttons-bottom {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-primary {
        background: #0066cc;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #0052a3;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #28a745;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    /* ===== الشارات ===== */
    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background: #d4edda;
        color: #155724;
    }

    .badge-in-progress {
        background: #cce5ff;
        color: #004085;
    }

    .badge-waiting {
        background: #fff3cd;
        color: #856404;
    }

    /* ===== حالة فارغة ===== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .empty-state p {
        margin: 0;
    }

    .text-muted {
        color: #6c757d;
    }

    /* ===== تصميم متجاوب ===== */
    @media (max-width: 768px) {
        .visit-details-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .patient-summary-card {
            flex-direction: column;
            text-align: center;
        }

        .action-buttons-bottom {
            flex-direction: column;
        }

        .btn-secondary,
        .btn-primary,
        .btn-success {
            width: 100%;
            justify-content: center;
        }

        .prescriptions-list {
            grid-template-columns: 1fr;
        }
    }
</style>