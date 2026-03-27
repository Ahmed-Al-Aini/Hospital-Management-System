<!-- views/reports/consumption.php -->
<div class="report-page-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> تقرير استهلاك الأدوية</h1>
            <p class="subtitle">عرض كمية الاستهلاك اليومي أو الشهري للأدوية مع تحليل الاتجاهات</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>
            <a href="<?php echo BASE_URL; ?>report" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <!-- فلتر الفترة -->
    <div class="filter-card">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> من تاريخ</label>
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> إلى تاريخ</label>
                    <input type="date" name="end_date" value="<?php echo $end_date; ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chart-line"></i> نوع التقرير</label>
                    <select name="type">
                        <option value="daily" <?php echo ($type ?? '') == 'daily' ? 'selected' : ''; ?>>يومي</option>
                        <option value="monthly" <?php echo ($type ?? '') == 'monthly' ? 'selected' : ''; ?>>شهري</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> عرض
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- جدول البيانات -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-table"></i> بيانات الاستهلاك</h3>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> طباعة
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>الدواء</th>
                            <th>الكمية المستهلكة</th>
                            <th>عدد الوصفات</th>
                            <th>متوسط الاستهلاك اليومي</th>
                            <th>الاتجاه</th>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="5" class="empty-table">
                                    <i class="fas fa-chart-line"></i>
                                    <p>لا توجد بيانات للفترة المحددة</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td>
                                        <div class="medicine-name">
                                            <i class="fas fa-capsules"></i>
                                            <?php echo htmlspecialchars($item->name); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stock-badge consumption">
                                            <?php echo $item->total_consumed; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $item->prescription_count; ?></td>
                                    <td><?php echo round($item->total_consumed / 30, 1); ?></td>
                                    <td>
                                        <?php
                                        $trend = $item->trend ?? 'stable';
                                        if ($trend == 'up'):
                                        ?>
                                            <span class="trend up"><i class="fas fa-arrow-up"></i> متزايد</span>
                                        <?php elseif ($trend == 'down'): ?>
                                            <span class="trend down"><i class="fas fa-arrow-down"></i> متناقص</span>
                                        <?php else: ?>
                                            <span class="trend stable"><i class="fas fa-minus"></i> ثابت</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ملخص إحصائيات -->
    <div class="stats-summary">
        <div class="summary-card">
            <i class="fas fa-chart-simple"></i>
            <div>
                <h4>إجمالي الاستهلاك</h4>
                <p><?php echo array_sum(array_column($data, 'total_consumed')); ?> وحدة</p>
            </div>
        </div>
        <div class="summary-card">
            <i class="fas fa-prescription"></i>
            <div>
                <h4>إجمالي الوصفات</h4>
                <p><?php echo array_sum(array_column($data, 'prescription_count')); ?> وصفة</p>
            </div>
        </div>
        <div class="summary-card">
            <i class="fas fa-calendar-week"></i>
            <div>
                <h4>المدة</h4>
                <p><?php echo $start_date; ?> - <?php echo $end_date; ?></p>
            </div>
        </div>
    </div>
</div>

<style>
    /* إضافة التنسيقات المطلوبة للتقرير */
    .report-page-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 25px;
        background: #f8f9fa;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filter-form .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: flex-end;
    }

    .filter-form .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        color: #6c757d;
    }

    .filter-form input,
    .filter-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .btn-filter {
        background: #0066cc;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
    }

    .btn-print {
        background: #6c757d;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8f9fa;
        padding: 12px;
        text-align: right;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .data-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
    }

    .stock-badge.consumption {
        background: #e3f2fd;
        color: #0066cc;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .trend {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
    }

    .trend.up {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .trend.down {
        background: #fee9ed;
        color: #dc3545;
    }

    .trend.stable {
        background: #f8f9fa;
        color: #6c757d;
    }

    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .summary-card i {
        font-size: 32px;
        color: #0066cc;
    }

    .summary-card h4 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #6c757d;
    }

    .summary-card p {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #1e2b37;
    }
</style>