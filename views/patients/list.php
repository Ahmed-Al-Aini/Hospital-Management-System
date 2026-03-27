<!-- views/patient/list.php -->
<div class="patients-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>قائمة المرضى</h1>
            <p class="subtitle">إدارة وعرض جميع المرضى المسجلين في النظام</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>

            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR, ROLE_SECRETARY])): ?>
                <a href="<?php echo BASE_URL; ?>patient/create" class="btn-add">
                    <i class="fas fa-plus"></i> إضافة مريض
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- بطاقات إحصائيات حقيقية -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['total']; ?></h3>
                <p>إجمالي المرضى</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-male"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['males']; ?></h3>
                <p>ذكور</p>
                <small><?php echo $stats['total'] > 0 ? round(($stats['males'] / $stats['total']) * 100) : 0; ?>%</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-female"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['females']; ?></h3>
                <p>إناث</p>
                <small><?php echo $stats['total'] > 0 ? round(($stats['females'] / $stats['total']) * 100) : 0; ?>%</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['today']; ?></h3>
                <p>جدد اليوم</p>
            </div>
        </div>
    </div>

    <!-- جدول المرضى -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> قائمة المرضى</h3>
            <div class="card-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchPatient" placeholder="بحث عن مريض...">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>رقم الهوية</th>
                            <th>الهاتف</th>
                            <th>فصيلة الدم</th>
                            <th>الجنس</th>
                            <th>العمر</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="patientsTableBody">
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="8" class="empty-table">
                                    <i class="fas fa-inbox"></i>
                                    <p>لا يوجد مرضى مسجلين</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $index => $patient): ?>
                                <?php
                                // حساب العمر إذا كان تاريخ الميلاد موجوداً
                                $age = '';
                                if ($patient->birth_date) {
                                    $birth = new DateTime($patient->birth_date);
                                    $now = new DateTime();
                                    $age = $birth->diff($now)->y;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="patient-name">
                                            <i class="fas fa-user-circle"></i>
                                            <?php echo htmlspecialchars($patient->name); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($patient->national_id ?? '-'); ?></td>
                                    <td>
                                        <?php if ($patient->phone): ?>
                                            <a href="tel:<?php echo $patient->phone; ?>" class="phone-link">
                                                <i class="fas fa-phone"></i> <?php echo $patient->phone; ?>
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $patient->gender == 'male' ? 'info' : 'success'; ?>">
                                            <i class="fas fa-<?php echo $patient->gender == 'male' ? 'mars' : 'venus'; ?>"></i>
                                            <?php echo $patient->gender == 'male' ? 'ذكر' : ($patient->gender == 'female' ? 'أنثى' : '-'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($age): ?>
                                            <span class="age-badge">
                                                <i class="fas fa-birthday-cake"></i>
                                                <?php echo $age; ?> سنة
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="date-cell">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo date('Y-m-d', strtotime($patient->created_at)); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $patient->id; ?>"
                                                class="btn-icon" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR])): ?>
                                                <a href="<?php echo BASE_URL; ?>patient/edit/<?php echo $patient->id; ?>"
                                                    class="btn-icon" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (Auth::has_Role(ROLE_DOCTOR)): ?>
                                                <a href="<?php echo BASE_URL; ?>prescription/create?patient_id=<?php echo $patient->id; ?>"
                                                    class="btn-icon" title="إنشاء وصفة">
                                                    <i class="fas fa-prescription"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- توزيع المرضى (بيانات حقيقية) -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie"></i> توزيع المرضى</h3>
        </div>
        <div class="card-body">
            <div class="impact-grid">
                <div class="impact-item">
                    <span class="impact-label">الأطفال (أقل من 18):</span>
                    <span class="impact-value"><?php echo $stats['children']; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">كبار السن (فوق 60):</span>
                    <span class="impact-value"><?php echo $stats['elderly']; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">مكررين (زيارتان فأكثر):</span>
                    <span class="impact-value"><?php echo $stats['repeat']; ?></span>
                </div>
                <div class="impact-item">
                    <span class="impact-label">جدد هذا الشهر:</span>
                    <span class="impact-value"><?php echo $stats['monthly'] ?? 0; ?></span>
                </div>
            </div>

            <!-- توزيع الأعمار -->
            <?php if (!empty($ageDistribution)): ?>
                <div class="age-distribution" style="margin-top: 20px;">
                    <h4>توزيع الفئات العمرية</h4>
                    <div class="age-bars">
                        <?php
                        $total = array_sum($ageDistribution);
                        foreach ($ageDistribution as $group => $count):
                            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                        ?>
                            <div class="age-bar-item">
                                <span class="age-label"><?php echo $group; ?></span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                                </div>
                                <span class="age-count"><?php echo $count; ?> (<?php echo $percentage; ?>%)</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript للبحث المباشر -->
<script>
    document.getElementById('searchPatient').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('#patientsTableBody tr');

        rows.forEach(row => {
            if (row.querySelector('.empty-table')) return; // تجاهل صف "لا يوجد بيانات"

            let name = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            let nationalId = row.cells[2]?.textContent.toLowerCase() || '';
            let phone = row.cells[3]?.textContent.toLowerCase() || '';

            if (name.includes(searchText) || nationalId.includes(searchText) || phone.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    /* ===== التصميم العام ===== */
    .patients-container {
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

    /* ===== زر الإضافة ===== */
    .btn-add {
        background: #0066cc;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0, 102, 204, 0.2);
    }

    .btn-add:hover {
        background: #0052a3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
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
        background: #e3f2fd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon i {
        font-size: 24px;
        color: #0066cc;
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

    .stat-details small {
        color: #28a745;
        font-size: 12px;
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

    .card-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* ===== صندوق البحث ===== */
    .search-box {
        position: relative;
        min-width: 250px;
    }

    .search-box i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .search-box input {
        width: 100%;
        padding: 8px 35px 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    /* ===== الجدول ===== */
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
    }

    .table th {
        background: #f8f9fa;
        padding: 15px 12px;
        color: #1e2b37;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
    }

    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #e9ecef;
        color: #1e2b37;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    /* ===== صف فارغ ===== */
    .empty-table {
        text-align: center;
        padding: 50px !important;
        color: #6c757d;
    }

    .empty-table i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    /* ===== اسم المريض مع أيقونة ===== */
    .patient-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .patient-name i {
        color: #0066cc;
        font-size: 20px;
    }

    /* ===== رابط الهاتف ===== */
    .phone-link {
        color: #0066cc;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .phone-link:hover {
        text-decoration: underline;
    }

    /* ===== الشارات ===== */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-info {
        background: #e3f2fd;
        color: #0066cc;
    }

    .badge-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge i {
        font-size: 12px;
    }

    /* ===== شارة العمر ===== */
    .age-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 12px;
        color: #6c757d;
    }

    .age-badge i {
        color: #0066cc;
    }

    /* ===== تاريخ التسجيل ===== */
    .date-cell {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #6c757d;
        font-size: 13px;
    }

    .date-cell i {
        color: #0066cc;
    }

    /* ===== أزرار الإجراءات ===== */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-icon {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f8f9fa;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-icon:hover {
        background: #0066cc;
        color: white;
        transform: translateY(-2px);
    }

    /* ===== توزيع الأعمار ===== */
    .age-distribution h4 {
        margin: 0 0 15px 0;
        color: #1e2b37;
        font-size: 16px;
    }

    .age-bars {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .age-bar-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .age-label {
        min-width: 80px;
        color: #6c757d;
        font-size: 13px;
    }

    .progress-bar {
        flex: 1;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #0066cc;
        border-radius: 4px;
        transition: width 0.3s;
    }

    .age-count {
        min-width: 80px;
        color: #1e2b37;
        font-size: 12px;
        font-weight: 500;
    }

    /* ===== أثر المرضى ===== */
    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
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
        color: #0066cc;
        font-weight: 700;
        font-size: 18px;
    }

    /* ===== تصميم متجاوب ===== */
    @media (max-width: 768px) {
        .patients-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-box {
            width: 100%;
        }

        .action-buttons {
            justify-content: flex-start;
        }

        .impact-grid {
            grid-template-columns: 1fr 1fr;
        }

        .age-bar-item {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-date {
            width: 100%;
            justify-content: center;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .impact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>