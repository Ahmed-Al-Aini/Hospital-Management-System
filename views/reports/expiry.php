<!-- views/reports/expiry.php -->
<div class="report-page-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-hourglass-end"></i> تقرير الأدوية منتهية الصلاحية</h1>
            <p class="subtitle">عرض الأدوية التي انتهت صلاحيتها أو قاربت على الانتهاء</p>
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
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($near_expiry); ?></h3>
                <p>أدوية قريبة الانتهاء</p>
                <small>خلال 30 يوماً</small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $expiredCount ?? 0; ?></h3>
                <p>أدوية منتهية</p>
            </div>
        </div>
    </div>

    <!-- قائمة الأدوية -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> الأدوية قريبة الانتهاء</h3>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchExpiry" placeholder="بحث عن دواء...">
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($near_expiry)): ?>
                <div class="empty-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <p>لا توجد أدوية قريبة الانتهاء</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="expiry-table">
                        <thead>
                            <tr>
                                <th>الدواء</th>
                                <th>الكمية</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الأيام المتبقية</th>
                                <th>الحالة</th>
                                <th>الإجراء</th>
                        </thead>
                        <tbody>
                            <?php foreach ($near_expiry as $item): ?>
                                <?php
                                $daysLeft = (strtotime($item->expiry_date) - time()) / (60 * 60 * 24);
                                $daysLeft = round($daysLeft);

                                if ($daysLeft <= 0):
                                    $status = 'expired';
                                    $statusText = 'منتهية';
                                    $action = 'إتلاف';
                                elseif ($daysLeft <= 7):
                                    $status = 'critical';
                                    $statusText = 'حرجة';
                                    $action = 'استخدام عاجل';
                                elseif ($daysLeft <= 15):
                                    $status = 'warning';
                                    $statusText = 'تنبيه';
                                    $action = 'مراجعة';
                                else:
                                    $status = 'normal';
                                    $statusText = 'طبيعي';
                                    $action = 'متابعة';
                                endif;
                                ?>
                                <tr class="expiry-row <?php echo $status; ?>">
                                    <td>
                                        <div class="medicine-name">
                                            <i class="fas fa-capsules"></i>
                                            <?php echo htmlspecialchars($item->name); ?>
                                        </div>
                                    </td>
                                    <td><?php echo $item->quantity; ?></td>
                                    <td>
                                        <span class="expiry-date <?php echo $status; ?>">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo date('Y-m-d', strtotime($item->expiry_date)); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="days-badge <?php echo $status; ?>">
                                            <?php echo $daysLeft; ?> يوم
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $status; ?>">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>medicine/edit/<?php echo $item->id; ?>" class="action-link">
                                            <i class="fas fa-edit"></i> <?php echo $action; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchExpiry').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('.expiry-table tbody tr');

        rows.forEach(row => {
            let name = row.querySelector('.medicine-name')?.textContent.toLowerCase() || '';
            if (name.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    .expiry-table {
        width: 100%;
        border-collapse: collapse;
    }

    .expiry-table th {
        background: #f8f9fa;
        padding: 12px;
        text-align: right;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .expiry-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .expiry-row.expired {
        background: #fee9ed;
    }

    .expiry-row.critical {
        background: #fff3e0;
    }

    .expiry-row.warning {
        background: #fff8e7;
    }

    .expiry-date {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .expiry-date.expired {
        color: #dc3545;
        font-weight: 600;
    }

    .expiry-date.critical {
        color: #f57c00;
        font-weight: 600;
    }

    .days-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .days-badge.expired {
        background: #dc3545;
        color: white;
    }

    .days-badge.critical {
        background: #f57c00;
        color: white;
    }

    .days-badge.warning {
        background: #ffc107;
        color: #1e2b37;
    }

    .days-badge.normal {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.expired {
        background: #dc3545;
        color: white;
    }

    .status-badge.critical {
        background: #f57c00;
        color: white;
    }

    .status-badge.warning {
        background: #ffc107;
        color: #1e2b37;
    }

    .status-badge.normal {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .action-link {
        color: #0066cc;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .action-link:hover {
        text-decoration: underline;
    }
</style>