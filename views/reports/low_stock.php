<!-- views/reports/low-stock.php -->
<div class="report-page-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exclamation-triangle"></i> تقرير الأدوية المنخفضة</h1>
            <p class="subtitle">عرض قائمة الأدوية التي وصلت للحد الأدنى أو أقل مع توصيات إعادة التزويد</p>
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

    <!-- بطاقات إحصائيات -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($low_stock); ?></h3>
                <p>أدوية منخفضة</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-skull-crossbones"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($critical_stock); ?></h3>
                <p>مخزون حرج</p>
            </div>
        </div>
    </div>

    <!-- الأدوية المنخفضة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-triangle"></i> الأدوية المنخفضة المخزون</h3>
        </div>
        <div class="card-body">
            <?php if (empty($low_stock) && empty($critical_stock)): ?>
                <div class="empty-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <p>لا توجد أدوية منخفضة المخزون أو حرجة</p>
                </div>
            <?php else: ?>
                <div class="stock-alerts">
                    <?php foreach ($critical_stock as $item): ?>
                        <div class="alert-card critical">
                            <div class="alert-icon">
                                <i class="fas fa-skull-crossbones"></i>
                            </div>
                            <div class="alert-details">
                                <h4><?php echo htmlspecialchars($item->name); ?></h4>
                                <p>الكمية الحالية: <strong><?php echo $item->current_stock; ?></strong> | الحد الحرج: <?php echo $item->critical_quantity; ?></p>
                                <div class="progress-bar-alert">
                                    <div class="progress-fill critical" style="width: <?php echo ($item->current_stock / $item->critical_quantity) * 100; ?>%;"></div>
                                </div>
                            </div>
                            <div class="alert-action">
                                <a href="<?php echo BASE_URL; ?>medicine/addStock/<?php echo $item->id; ?>" class="btn-urgent">طلب عاجل</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($low_stock as $item): ?>
                        <div class="alert-card low">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-details">
                                <h4><?php echo htmlspecialchars($item->name); ?></h4>
                                <p>الكمية الحالية: <strong><?php echo $item->current_stock; ?></strong> | الحد الأدنى: <?php echo $item->min_quantity; ?></p>
                                <div class="progress-bar-alert">
                                    <div class="progress-fill low" style="width: <?php echo ($item->current_stock / $item->min_quantity) * 100; ?>%;"></div>
                                </div>
                            </div>
                            <div class="alert-action">
                                <a href="<?php echo BASE_URL; ?>medicine/addStock/<?php echo $item->id; ?>" class="btn-reorder">إعادة تزويد</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .stock-alerts {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .alert-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s;
    }

    .alert-card.critical {
        border-right: 4px solid #dc3545;
        background: #fee9ed;
    }

    .alert-card.low {
        border-right: 4px solid #f57c00;
        background: #fff3e0;
    }

    .alert-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .alert-card.critical .alert-icon {
        color: #dc3545;
    }

    .alert-card.low .alert-icon {
        color: #f57c00;
    }

    .alert-icon i {
        font-size: 24px;
    }

    .alert-details {
        flex: 1;
    }

    .alert-details h4 {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: #1e2b37;
    }

    .alert-details p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
    }

    .progress-bar-alert {
        width: 100%;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill.critical {
        height: 100%;
        background: #dc3545;
    }

    .progress-fill.low {
        height: 100%;
        background: #f57c00;
    }

    .btn-urgent {
        background: #dc3545;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-urgent:hover {
        background: #c82333;
    }

    .btn-reorder {
        background: #f57c00;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-reorder:hover {
        background: #e06e00;
    }

    .empty-alert-success {
        text-align: center;
        padding: 50px;
        background: #e8f5e9;
        border-radius: 12px;
        color: #2e7d32;
    }

    .empty-alert-success i {
        font-size: 48px;
        margin-bottom: 15px;
    }
</style>