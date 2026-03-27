<!-- views/reports/prescriptions.php -->
<div class="report-page-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-prescription"></i> تقرير الوصفات الطبية</h1>
            <p class="subtitle">عرض إحصائيات الوصفات الطبية حسب الأطباء والأقسام والفترات الزمنية</p>
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
                    <input type="date" name="start_date" value="<?php echo $start_date ?? date('Y-m-d', strtotime('-30 days')); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> إلى تاريخ</label>
                    <input type="date" name="end_date" value="<?php echo $end_date ?? date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chart-line"></i> نوع التقرير</label>
                    <select name="type">
                        <option value="daily" <?php echo ($type ?? 'daily') == 'daily' ? 'selected' : ''; ?>>يومي</option>
                        <option value="weekly" <?php echo ($type ?? '') == 'weekly' ? 'selected' : ''; ?>>أسبوعي</option>
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

    <!-- بطاقات إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalPrescriptions ?? 0; ?></h3>
                <p>إجمالي الوصفات</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $dispensedCount ?? 0; ?></h3>
                <p>تم الصرف</p>
                <small><?php echo $dispensedPercentage ?? 0; ?>%</small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $pendingCount ?? 0; ?></h3>
                <p>قيد الانتظار</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $avgDaily ?? 0; ?></h3>
                <p>متوسط يومي</p>
            </div>
        </div>
    </div>

    <!-- جدول الوصفات اليومية -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> الوصفات حسب التاريخ</h3>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> طباعة
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>عدد الوصفات</th>
                            <th>تم الصرف</th>
                            <th>قيد الانتظار</th>
                            <th>نسبة الصرف</th>
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
                                <?php
                                $pending = $item->count - $item->dispensed;
                                $percentage = $item->count > 0 ? round(($item->dispensed / $item->count) * 100) : 0;
                                ?>
                                <tr>
                                    <td>
                                        <div class="date-cell">
                                            <i class="fas fa-calendar-day"></i>
                                            <?php echo date('Y-m-d', strtotime($item->date)); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-count"><?php echo $item->count; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-dispensed"><?php echo $item->dispensed; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-pending"><?php echo $pending; ?></span>
                                    </td>
                                    <td>
                                        <div class="progress-cell">
                                            <div class="progress-bar-small">
                                                <div class="progress-fill-small" style="width: <?php echo $percentage; ?>%;"></div>
                                            </div>
                                            <span class="percentage"><?php echo $percentage; ?>%</span>
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

    <!-- توزيع الوصفات حسب الأطباء (اختياري) -->
    <?php if (!empty($doctorsData)): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-md"></i> الوصفات حسب الأطباء</h3>
            </div>
            <div class="card-body">
                <div class="doctors-grid">
                    <?php foreach ($doctorsData as $doctor): ?>
                        <div class="doctor-card">
                            <div class="doctor-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="doctor-info">
                                <h4><?php echo htmlspecialchars($doctor->name); ?></h4>
                                <p><?php echo $doctor->prescription_count; ?> وصفة</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* تنسيقات إضافية */
    .date-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1e2b37;
    }

    .badge-count {
        display: inline-block;
        background: #e3f2fd;
        color: #0066cc;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .badge-dispensed {
        display: inline-block;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .badge-pending {
        display: inline-block;
        background: #fff3e0;
        color: #f57c00;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .progress-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 120px;
    }

    .progress-bar-small {
        flex: 1;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill-small {
        height: 100%;
        background: #0066cc;
        border-radius: 3px;
    }

    .percentage {
        font-size: 12px;
        font-weight: 600;
        color: #0066cc;
        min-width: 40px;
    }

    .doctors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .doctor-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s;
    }

    .doctor-card:hover {
        background: #e9ecef;
        transform: translateX(-5px);
    }

    .doctor-icon {
        width: 45px;
        height: 45px;
        background: #e3f2fd;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .doctor-icon i {
        font-size: 22px;
        color: #0066cc;
    }

    .doctor-info h4 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #1e2b37;
    }

    .doctor-info p {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0066cc;
    }
</style>