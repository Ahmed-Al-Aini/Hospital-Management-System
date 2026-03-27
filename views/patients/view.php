<!-- views/patient/view.php -->
<div class="patient-view-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>بيانات المريض</h1>
            <p class="subtitle">عرض تفاصيل المريض <?php echo htmlspecialchars($patient->name); ?></p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>

            <div class="action-buttons-group">
                <a href="<?php echo BASE_URL; ?>patient/list" class="btn-back">
                    <i class="fas fa-arrow-right"></i> عودة
                </a>

                <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR])): ?>
                    <a href="<?php echo BASE_URL; ?>patient/edit/<?php echo $patient->id; ?>" class="btn-edit">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                <?php endif; ?>

                <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
                    <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $patient->id; ?>" class="btn-prescription">
                        <i class="fas fa-prescription"></i> إنشاء وصفة
                    </a>
                <?php endif; ?>
                <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
                    <a href="<?php echo BASE_URL; ?>visit/create?patient_id=<?php echo $patient->id; ?>" class="btn-visit">
                        <i class="fas fa-notes-medical"></i> تسجيل زيارة
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- بطاقات إحصائيات المريض -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $patient->visits_count ?? 0; ?></h3>
                <p>عدد الزيارات</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $patient->last_visit ? time_elapsed_string($patient->last_visit) : '-'; ?></h3>
                <p>آخر زيارة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('Y-m-d', strtotime($patient->created_at)); ?></h3>
                <p>تاريخ التسجيل</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-<?php echo $patient->gender == 'male' ? 'mars' : 'venus'; ?>"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $patient->gender == 'male' ? 'ذكر' : ($patient->gender == 'female' ? 'أنثى' : '-'); ?></h3>
                <p>الجنس</p>
            </div>
        </div>
    </div>

    <!-- بطاقة معلومات المريض -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-circle"></i> المعلومات الشخصية</h3>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <!-- الاسم -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user"></i>
                        <span>الاسم الكامل</span>
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($patient->name); ?></div>
                </div>

                <!-- رقم الهوية -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-card"></i>
                        <span>رقم الهوية</span>
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($patient->national_id ?? '-'); ?></div>
                </div>

                <!-- تاريخ الميلاد -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-birthday-cake"></i>
                        <span>تاريخ الميلاد</span>
                    </div>
                    <div class="info-value">
                        <?php
                        if ($patient->birth_date) {
                            $birthDate = new DateTime($patient->birth_date);
                            $today = new DateTime();
                            $age = $birthDate->diff($today)->y;
                            echo date('Y-m-d', strtotime($patient->birth_date)) . " (العمر: $age سنة)";
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-birthday-cake"></i>
                        <span> فصيلة الدم </span>
                    </div>
                    <div class="info-value">
                        <?php
                        $bloodTypes = [
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-'
                        ];
                        echo $bloodTypes[$patient->blood_type] ?? '-';
                        ?>
                    </div>
                </div>

                <!-- الهاتف -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-phone"></i>
                        <span>رقم الهاتف</span>
                    </div>
                    <div class="info-value">
                        <?php if ($patient->phone): ?>
                            <a href="tel:<?php echo $patient->phone; ?>" class="phone-link">
                                <?php echo $patient->phone; ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                </div>

                <!-- البريد الإلكتروني (إذا كان موجوداً) -->
                <?php if (isset($patient->email)): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            <span>البريد الإلكتروني</span>
                        </div>
                        <div class="info-value">
                            <a href="mailto:<?php echo $patient->email; ?>"><?php echo $patient->email; ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- العنوان (إذا كان موجوداً) -->
                <?php if (isset($patient->address)): ?>
                    <div class="info-item full-width">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>العنوان</span>
                        </div>
                        <div class="info-value"><?php echo $patient->address; ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- سجل الزيارات السابقة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> سجل الزيارات</h3>
            <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
                <a href="<?php echo BASE_URL; ?>visit/create?patient_id=<?php echo $patient->id; ?>" class="btn-add-small">
                    <i class="fas fa-plus"></i> زيارة جديدة
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($patient->visits)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>لا توجد زيارات سابقة لهذا المريض</p>
                </div>
            <?php else: ?>
                <div class="visits-timeline">
                    <?php foreach ($patient->visits as $visit): ?>
                        <div class="visit-item">
                            <div class="visit-date">
                                <span class="day"><?php echo date('d', strtotime($visit->visit_date)); ?></span>
                                <span class="month"><?php echo date('M', strtotime($visit->visit_date)); ?></span>
                                <span class="year"><?php echo date('Y', strtotime($visit->visit_date)); ?></span>
                            </div>
                            <div class="visit-content">
                                <h4>زيارة رقم <?php echo $visit->id; ?></h4>
                                <p class="visit-doctor">
                                    <i class="fas fa-user-md"></i> د. <?php echo htmlspecialchars($visit->doctor_name ?? 'غير محدد'); ?>
                                </p>
                                <?php if (!empty($visit->complaint)): ?>
                                    <p class="visit-complaint">
                                        <i class="fas fa-head-side-cough"></i> <?php echo htmlspecialchars($visit->complaint); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($visit->diagnosis)): ?>
                                    <p class="visit-diagnosis">
                                        <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($visit->diagnosis); ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>visit/views/<?php echo $visit->id; ?>" class="btn-view-small">
                                    عرض التفاصيل <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- الوصفات السابق الوصفات السابقة -->
    <?php if (!empty($patient->prescriptions)): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-prescription"></i> آخر الوصفات</h3>
                <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
                    <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $patient->id; ?>" class="btn-add-small">
                        <i class="fas fa-plus"></i> وصفة جديدة
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="prescriptions-mini-list">
                    <?php foreach ($patient->prescriptions as $presc): ?>
                        <div class="prescription-mini-item">
                            <div class="prescription-mini-header">
                                <span class="prescription-mini-id">وصفة #<?php echo $presc->id; ?></span>
                                <span class="prescription-mini-date"><?php echo date('Y-m-d', strtotime($presc->created_at)); ?></span>
                            </div>
                            <div class="prescription-mini-body">
                                <span class="badge badge-<?php echo $presc->status == 'dispensed' ? 'success' : 'warning'; ?>">
                                    <?php echo $presc->status == 'dispensed' ? 'تم الصرف' : 'قيد الانتظار'; ?>
                                </span>
                                <span class="prescription-mini-doctor">
                                    <i class="fas fa-user-md"></i> د. <?php echo htmlspecialchars($presc->doctor_name ?? 'غير محدد'); ?>
                                </span>
                            </div>
                            <div class="prescription-mini-footer">
                                <a href="<?php echo BASE_URL; ?>prescription/views/<?php echo $presc->id; ?>" class="btn-view-mini">
                                    عرض <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <style>
        /* ===== قائمة الزيارات المصغرة ===== */
        .visits-timeline {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .visit-item {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .visit-item:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }

        .visit-date {
            min-width: 80px;
            text-align: center;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .visit-date .day {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #0066cc;
            line-height: 1;
        }

        .visit-date .month {
            display: block;
            font-size: 14px;
            color: #6c757d;
        }

        .visit-date .year {
            display: block;
            font-size: 12px;
            color: #999;
        }

        .visit-content {
            flex: 1;
        }

        .visit-content h4 {
            margin: 0 0 8px 0;
            color: #1e2b37;
            font-size: 16px;
        }

        .visit-doctor {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .visit-doctor i {
            color: #0066cc;
        }

        .visit-complaint,
        .visit-diagnosis {
            color: #1e2b37;
            font-size: 14px;
            margin-bottom: 5px;
            padding: 5px;
            background: white;
            border-radius: 6px;
        }

        .visit-complaint i,
        .visit-diagnosis i {
            color: #0066cc;
            margin-left: 5px;
        }

        .btn-view-small {
            color: #0066cc;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-small:hover {
            text-decoration: underline;
        }

        /* ===== قائمة الوصفات المصغرة ===== */
        .prescriptions-mini-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 15px;
        }

        .prescription-mini-item {
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            border-right: 4px solid #0066cc;
            transition: all 0.3s;
        }

        .prescription-mini-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .prescription-mini-header {
            padding: 10px 12px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prescription-mini-id {
            font-weight: 600;
            color: #0066cc;
            font-size: 13px;
        }

        .prescription-mini-date {
            color: #6c757d;
            font-size: 11px;
        }

        .prescription-mini-body {
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prescription-mini-doctor {
            color: #6c757d;
            font-size: 12px;
        }

        .prescription-mini-doctor i {
            color: #0066cc;
        }

        .prescription-mini-footer {
            padding: 10px 12px;
            border-top: 1px solid #dee2e6;
            text-align: left;
        }

        .btn-view-mini {
            color: #0066cc;
            text-decoration: none;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-mini:hover {
            text-decoration: underline;
        }

        .btn-add-small {
            background: #0066cc;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-add-small:hover {
            background: #0052a3;
        }

        /* ===== التصميم العام ===== */
        .patient-view-container {
            max-width: 1400px;
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
            gap: 15px;
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

        .action-buttons-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ===== أزرار الإجراءات ===== */
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

        .btn-edit {
            background: #ffc107;
            color: #1e2b37;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-prescription {
            background: #28a745;
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

        .btn-prescription:hover {
            background: #218838;
            transform: translateY(-2px);
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
            font-size: 28px;
            margin: 0;
            color: #1e2b37;
            font-weight: 700;
        }

        .stat-details p {
            margin: 5px 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* ===== البطاقة الرئيسية ===== */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
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
            background: white;
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

        /* ===== شبكة المعلومات ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item:hover {
            background: #e9ecef;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .info-label i {
            color: #0066cc;
            width: 16px;
        }

        .info-value {
            color: #1e2b37;
            font-size: 16px;
            font-weight: 500;
            padding-right: 24px;
        }

        .phone-link {
            color: #0066cc;
            text-decoration: none;
        }

        .phone-link:hover {
            text-decoration: underline;
        }

        /* ===== سجل الزيارات ===== */
        .visits-timeline {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .visit-item {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .visit-item:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }

        .visit-date {
            min-width: 80px;
            text-align: center;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .visit-date .day {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #0066cc;
            line-height: 1;
        }

        .visit-date .month {
            display: block;
            font-size: 14px;
            color: #6c757d;
        }

        .visit-date .year {
            display: block;
            font-size: 12px;
            color: #999;
        }

        .visit-content {
            flex: 1;
        }

        .visit-content h4 {
            margin: 0 0 8px 0;
            color: #1e2b37;
            font-size: 16px;
        }

        .visit-doctor {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .visit-doctor i {
            color: #0066cc;
        }

        .visit-diagnosis {
            color: #1e2b37;
            font-size: 14px;
            margin-bottom: 10px;
            padding: 8px;
            background: white;
            border-radius: 6px;
        }

        .btn-view-small {
            color: #0066cc;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-small:hover {
            text-decoration: underline;
        }

        /* ===== الوصفات السابقة ===== */
        .prescriptions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }

        .prescription-card {
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            border-right: 4px solid #0066cc;
        }

        .prescription-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .prescription-header {
            padding: 15px;
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
        }

        .prescription-doctor {
            margin: 0 0 10px 0;
            color: #1e2b37;
            font-size: 14px;
        }

        .prescription-doctor i {
            color: #0066cc;
        }

        .prescription-footer {
            padding: 15px;
            border-top: 1px solid #dee2e6;
            text-align: left;
        }

        .btn-view {
            color: #0066cc;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view:hover {
            text-decoration: underline;
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

        /* ===== حالة فارغة ===== */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .empty-state p {
            margin: 0;
            font-size: 16px;
        }

        /* ===== تصميم متجاوب ===== */
        @media (max-width: 768px) {
            .patient-view-container {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons-group {
                flex-direction: column;
            }

            .btn-back,
            .btn-edit,
            .btn-prescription {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .visit-item {
                flex-direction: column;
                gap: 10px;
            }

            .visit-date {
                align-self: flex-start;
            }

            .prescriptions-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>