<!-- views/medicine/alerts.php -->
<div class="alerts-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>التنبيهات</h1>
            <p class="subtitle">مراقبة المخزون والأدوية القريبة من الانتهاء</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>

            <a href="<?php echo BASE_URL; ?>medicine/list" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <!-- بطاقات إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($alerts['low_stock']); ?></h3>
                <p>أدوية منخفضة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-skull-crossbones"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($alerts['critical_stock']); ?></h3>
                <p>مخزون حرج</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($alerts['near_expiry']); ?></h3>
                <p>قريبة الانتهاء</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo  count($alerts['sum'])?? 0; ?></h3>
                <p>إجمالي الأدوية</p>
            </div>
        </div>
    </div>

    <!-- شبكة التنبيهات -->
    <div class="alerts-grid">
        <!-- بطاقة الأدوية منخفضة المخزون -->
        <div class="alert-card low-stock">
            <div class="alert-card-header warning">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>أدوية منخفضة المخزون</h3>
                <span class="alert-count"><?php echo count($alerts['low_stock']); ?></span>
            </div>
            <div class="alert-card-body">
                <?php if (empty($alerts['low_stock'])): ?>
                    <div class="empty-alert">
                        <i class="fas fa-check-circle"></i>
                        <p>لا توجد أدوية منخفضة المخزون</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="alerts-table">
                            <thead>
                                <tr>
                                    <th>الدواء</th>
                                    <th>الكمية الحالية</th>
                                    <th>الحد الأدنى</th>
                                    <th>العجز</th>
                            </thead>
                            <tbody>
                                <?php foreach ($alerts['low_stock'] as $item): ?>
                                    <?php $deficit = $item->min_quantity - ($item->current_stock ?? 0); ?>
                                    <tr>
                                        <td>
                                            <div class="medicine-name">
                                                <i class="fas fa-capsules"></i>
                                                <?php echo htmlspecialchars($item->name); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="stock-badge low"><?php echo $item->current_stock ?? 0; ?></span>
                                        </td>
                                        <td><?php echo $item->min_quantity; ?></td>
                                        <td class="deficit"><?php echo $deficit > 0 ? '+' . $deficit : '0'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert-action">
                        <a href="<?php echo BASE_URL; ?>medicine/list" class="btn-action">
                            <i class="fas fa-plus-circle"></i> إعادة تزويد
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- بطاقة المخزون الحرج -->
        <div class="alert-card critical-stock">
            <div class="alert-card-header danger">
                <i class="fas fa-skull-crossbones"></i>
                <h3>مخزون حرج</h3>
                <span class="alert-count critical"><?php echo count($alerts['critical_stock']); ?></span>
            </div>
            <div class="alert-card-body">
                <?php if (empty($alerts['critical_stock'])): ?>
                    <div class="empty-alert">
                        <i class="fas fa-check-circle"></i>
                        <p>لا توجد أدوية في المخزون الحرج</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="alerts-table">
                            <thead>
                                <tr>
                                    <th>الدواء</th>
                                    <th>الكمية الحالية</th>
                                    <th>الحد الحرج</th>
                                    <th>العجز</th>
                            </thead>
                            <tbody>
                                <?php foreach ($alerts['critical_stock'] as $item): ?>
                                    <?php $deficit = $item->critical_quantity - ($item->current_stock ?? 0); ?>
                                    <tr class="critical-row">
                                        <td>
                                            <div class="medicine-name">
                                                <i class="fas fa-capsules"></i>
                                                <?php echo htmlspecialchars($item->name); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="stock-badge critical"><?php echo $item->current_stock ?? 0; ?></span>
                                        </td>
                                        <td><?php echo $item->critical_quantity; ?></td>
                                        <td class="deficit critical"><?php echo $deficit > 0 ? '+' . $deficit : '0'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert-action">
                        <a href="<?php echo BASE_URL; ?>medicine/list" class="btn-action urgent">
                            <i class="fas fa-truck"></i> طلب عاجل
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- بطاقة الأدوية قريبة الانتهاء -->
        <div class="alert-card near-expiry">
            <div class="alert-card-header info">
                <i class="fas fa-hourglass-half"></i>
                <h3>أدوية قريبة الانتهاء</h3>
                <span class="alert-count info"><?php echo count($alerts['near_expiry']); ?></span>
            </div>
            <div class="alert-card-body">
                <?php if (empty($alerts['near_expiry'])): ?>
                    <div class="empty-alert">
                        <i class="fas fa-check-circle"></i>
                        <p>لا توجد أدوية قريبة الانتهاء</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="alerts-table">
                            <thead>
                                <tr>
                                    <th>الدواء</th>
                                    <th>الكمية</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الأيام المتبقية</th>
                            </thead>
                            <tbody>
                                <?php foreach ($alerts['near_expiry'] as $item): ?>
                                    <?php
                                    $daysLeft = (strtotime($item->expiry_date) - time()) / (60 * 60 * 24);
                                    $daysLeft = round($daysLeft);
                                    $urgencyClass = $daysLeft <= 7 ? 'urgent' : ($daysLeft <= 15 ? 'warning' : 'normal');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="medicine-name">
                                                <i class="fas fa-capsules"></i>
                                                <?php echo htmlspecialchars($item->name); ?>
                                            </div>
                                        </td>
                                        <td><?php echo $item->quantity; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($item->expiry_date)); ?></td>
                                        <td>
                                            <span class="days-badge <?php echo $urgencyClass; ?>">
                                                <?php echo $daysLeft; ?> يوم
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert-action">
                        <a href="<?php echo BASE_URL; ?>medicine/list" class="btn-action info">
                            <i class="fas fa-chart-line"></i> مراجعة المخزون
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="info-content">
            <h4>نظام التنبيهات</h4>
            <p>• <strong>المخزون المنخفض:</strong> الكمية أقل من الحد الأدنى - يُنصح بإعادة التخزين<br>
                • <strong>المخزون الحرج:</strong> الكمية أقل من الحد الحرج - يجب التصرف فوراً<br>
                • <strong>قريب الانتهاء:</strong> الأدوية التي تنتهي صلاحيتها خلال 30 يوماً</p>
        </div>
    </div>
</div>

<style>
    /* ===== التصميم العام ===== */
    .alerts-container {
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

    .stat-icon.orange {
        background: #fff3e0;
        color: #f57c00;
    }

    .stat-icon.red {
        background: #fee9ed;
        color: #dc3545;
    }

    .stat-icon.blue {
        background: #e3f2fd;
        color: #0066cc;
    }

    .stat-icon.green {
        background: #e8f5e9;
        color: #2e7d32;
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

    /* ===== شبكة التنبيهات ===== */
    .alerts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .alert-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s;
    }

    .alert-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .alert-card-header {
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
    }

    .alert-card-header.warning {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);
    }

    .alert-card-header.danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .alert-card-header.info {
        background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%);
    }

    .alert-card-header i {
        font-size: 24px;
    }

    .alert-card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        flex: 1;
    }

    .alert-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-card-body {
        padding: 20px;
    }

    /* ===== جداول التنبيهات ===== */
    .table-responsive {
        overflow-x: auto;
    }

    .alerts-table {
        width: 100%;
        border-collapse: collapse;
    }

    .alerts-table th {
        background: #f8f9fa;
        padding: 10px 8px;
        text-align: right;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 1px solid #e9ecef;
    }

    .alerts-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #e9ecef;
        font-size: 13px;
    }

    .alerts-table tr:hover {
        background: #f8f9fa;
    }

    .critical-row {
        background: #fee9ed;
    }

    .medicine-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .medicine-name i {
        color: #0066cc;
        font-size: 14px;
    }

    /* ===== شارات المخزون ===== */
    .stock-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-badge.low {
        background: #fff3e0;
        color: #f57c00;
    }

    .stock-badge.critical {
        background: #fee9ed;
        color: #dc3545;
    }

    .deficit {
        font-weight: 600;
        color: #f57c00;
    }

    .deficit.critical {
        color: #dc3545;
    }

    /* ===== شارات الأيام المتبقية ===== */
    .days-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .days-badge.urgent {
        background: #fee9ed;
        color: #dc3545;
    }

    .days-badge.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .days-badge.normal {
        background: #e8f5e9;
        color: #2e7d32;
    }

    /* ===== حالة فارغة ===== */
    .empty-alert {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-alert i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .empty-alert p {
        margin: 0;
    }

    /* ===== أزرار الإجراءات ===== */
    .alert-action {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        text-align: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f8f9fa;
        color: #0066cc;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-action:hover {
        background: #0066cc;
        color: white;
        transform: translateY(-2px);
    }

    .btn-action.urgent:hover {
        background: #dc3545;
    }

    .btn-action.info:hover {
        background: #17a2b8;
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
        .alerts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .alerts-container {
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

        .header-date,
        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .alert-card-header {
            flex-wrap: wrap;
        }

        .info-card {
            flex-direction: column;
            text-align: center;
        }

        .alerts-table {
            min-width: 400px;
        }
    }
</style>