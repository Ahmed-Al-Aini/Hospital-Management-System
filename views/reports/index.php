<!-- views/reports/index.php -->
<div class="reports-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>التقارير</h1>
            <p class="subtitle">تحليل وإدارة بيانات النظام من خلال تقارير متنوعة</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>
        </div>
    </div>

    <!-- بطاقات إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalConsumption ?? 0; ?></h3>
                <p>إجمالي الاستهلاك الشهري</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalMedicines ?? 0; ?></h3>
                <p>إجمالي الأدوية</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalPrescriptions ?? 0; ?></h3>
                <p>إجمالي الوصفات</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $thisMonth ?? date('m'); ?></h3>
                <p>الشهر الحالي</p>
            </div>
        </div>
    </div>

    <!-- شبكة التقارير -->
    <div class="reports-grid">
        <!-- تقرير استهلاك الأدوية -->
        <div class="report-card">
            <div class="report-icon consumption">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="report-content">
                <h3>تقرير استهلاك الأدوية</h3>
                <p>عرض كمية الاستهلاك اليومي أو الشهري للأدوية مع تحليل الاتجاهات</p>
                <div class="report-meta">
                    <span><i class="fas fa-chart-simple"></i> تحليل كمي</span>
                    <span><i class="fas fa-calendar-week"></i> يومي/شهري</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/consumption" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- تقرير المخزون -->
        <div class="report-card">
            <div class="report-icon inventory">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="report-content">
                <h3>تقرير المخزون</h3>
                <p>عرض حالة المخزون الحالية لجميع الأدوية مع تنبيهات النقص والانتهاء</p>
                <div class="report-meta">
                    <span><i class="fas fa-check-circle"></i> حالة المخزون</span>
                    <span><i class="fas fa-exclamation-triangle"></i> تنبيهات</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/inventory" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- تقرير الوصفات -->
        <div class="report-card">
            <div class="report-icon prescriptions">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="report-content">
                <h3>تقرير الوصفات</h3>
                <p>عرض إحصائيات الوصفات الطبية حسب الأطباء والأقسام والفترات الزمنية</p>
                <div class="report-meta">
                    <span><i class="fas fa-user-md"></i> حسب الطبيب</span>
                    <span><i class="fas fa-building"></i> حسب القسم</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/prescriptions" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- تقرير حركة المخزون -->
        <div class="report-card">
            <div class="report-icon movements">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="report-content">
                <h3>تقرير حركة المخزون</h3>
                <p>عرض سجل حركات الإضافة والصرف مع تفاصيل المستخدمين والتواريخ</p>
                <div class="report-meta">
                    <span><i class="fas fa-plus-circle"></i> إضافات</span>
                    <span><i class="fas fa-minus-circle"></i> صرف</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/movements" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- تقرير الأدوية منخفضة المخزون -->
        <div class="report-card">
            <div class="report-icon low-stock">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="report-content">
                <h3>تقرير الأدوية المنخفضة</h3>
                <p>عرض قائمة الأدوية التي وصلت للحد الأدنى أو أقل مع توصيات إعادة التزويد</p>
                <div class="report-meta">
                    <span><i class="fas fa-chart-down"></i> منخفضة</span>
                    <span><i class="fas fa-skull-crossbones"></i> حرجة</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/lowStock" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- تقرير الأدوية منتهية الصلاحية -->
        <div class="report-card">
            <div class="report-icon expiry">
                <i class="fas fa-hourglass-end"></i>
            </div>
            <div class="report-content">
                <h3>تقرير الأدوية منتهية الصلاحية</h3>
                <p>عرض الأدوية التي انتهت صلاحيتها أو قاربت على الانتهاء</p>
                <div class="report-meta">
                    <span><i class="fas fa-calendar-times"></i> منتهية</span>
                    <span><i class="fas fa-hourglass-half"></i> قاربت</span>
                </div>
                <a href="<?php echo BASE_URL; ?>report/expiry" class="btn-report">
                    <i class="fas fa-eye"></i> عرض التقرير
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="info-content">
            <h4>تحليل البيانات</h4>
            <p>• يمكنك تصدير التقارير بصيغة PDF أو Excel<br>
                • تحديد الفترات الزمنية المطلوبة لعرض البيانات بدقة<br>
                • تحليل اتجاهات الاستهلاك لتخطيط أفضل للمخزون</p>
        </div>
    </div>
</div>

<style>
    /* ===== التصميم العام ===== */
    .reports-container {
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
        color: #2e7d32;
    }

    .stat-icon.orange {
        background: #fff3e0;
        color: #f57c00;
    }

    .stat-icon.purple {
        background: #f3e5f5;
        color: #7b1fa2;
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

    /* ===== شبكة التقارير ===== */
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .report-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        display: flex;
        position: relative;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .report-icon {
        width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .report-icon.consumption {
        background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%);
    }

    .report-icon.inventory {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .report-icon.prescriptions {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);
    }

    .report-icon.movements {
        background: linear-gradient(135deg, #6f42c1 0%, #9b59b6 100%);
    }

    .report-icon.low-stock {
        background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
    }

    .report-icon.expiry {
        background: linear-gradient(135deg, #fd7e14 0%, #ffb347 100%);
    }

    .report-icon i {
        font-size: 48px;
        color: white;
    }

    .report-content {
        flex: 1;
        padding: 25px;
    }

    .report-content h3 {
        margin: 0 0 10px 0;
        color: #1e2b37;
        font-size: 20px;
        font-weight: 700;
    }

    .report-content p {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .report-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .report-meta span {
        font-size: 12px;
        color: #6c757d;
        background: #f8f9fa;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .report-meta span i {
        font-size: 10px;
    }

    .btn-report {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0066cc;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-report i:last-child {
        transition: transform 0.3s;
    }

    .btn-report:hover {
        background: #0052a3;
        transform: translateY(-2px);
    }

    .btn-report:hover i:last-child {
        transform: translateX(-5px);
    }

    /* ===== بطاقة معلومات ===== */
    .info-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdef5 100%);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-icon i {
        font-size: 24px;
        color: #0066cc;
    }

    .info-content h4 {
        margin: 0 0 5px 0;
        color: #1e2b37;
        font-size: 16px;
    }

    .info-content p {
        margin: 0;
        color: #0066cc;
        font-size: 14px;
        line-height: 1.6;
    }

    /* ===== تصميم متجاوب ===== */
    @media (max-width: 1024px) {
        .reports-grid {
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .reports-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .reports-grid {
            grid-template-columns: 1fr;
        }

        .report-card {
            flex-direction: column;
        }

        .report-icon {
            width: 100%;
            padding: 30px;
        }

        .info-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>