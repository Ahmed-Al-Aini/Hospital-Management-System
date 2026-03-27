<!-- views/reports/movements.php -->
<div class="report-page-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> تقرير حركة المخزون</h1>
            <p class="subtitle">عرض سجل حركات الإضافة والصرف مع تفاصيل المستخدمين والتواريخ</p>
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
                    <label><i class="fas fa-filter"></i> نوع الحركة</label>
                    <select name="type">
                        <option value="all" <?php echo ($type ?? 'all') == 'all' ? 'selected' : ''; ?>>الكل</option>
                        <option value="add" <?php echo ($type ?? '') == 'add' ? 'selected' : ''; ?>>إضافة</option>
                        <option value="remove" <?php echo ($type ?? '') == 'remove' ? 'selected' : ''; ?>>صرف</option>
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

    <!-- بطاقات إحصائيات -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalAdded ?? 0; ?></h3>
                <p>إجمالي الإضافات</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-minus-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalRemoved ?? 0; ?></h3>
                <p>إجمالي الصرف</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $totalTransactions ?? 0; ?></h3>
                <p>إجمالي الحركات</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $activeUsers ?? 0; ?></h3>
                <p>مستخدمين نشطين</p>
            </div>
        </div>
    </div>

    <!-- جدول الحركات -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> سجل الحركات</h3>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchMovement" placeholder="بحث عن دواء أو مستخدم...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="movements-table">
                    <thead>
                        <tr>
                            <th>التاريخ والوقت</th>
                            <th>الدواء</th>
                            <th>نوع الحركة</th>
                            <th>الكمية</th>
                            <th>المستخدم</th>
                            <th>المرجع</th>
                    </thead>
                    <tbody>
                        <?php if (empty($movements)): ?>
                            <tr>
                                <td colspan="6" class="empty-table">
                                    <i class="fas fa-exchange-alt"></i>
                                    <p>لا توجد حركات للفترة المحددة</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movements as $move): ?>
                                <tr class="movement-row <?php echo $move->type; ?>">
                                    <td>
                                        <div class="datetime-cell">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo date('Y-m-d', strtotime($move->created_at)); ?>
                                            <i class="far fa-clock"></i>
                                            <?php echo date('H:i', strtotime($move->created_at)); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="medicine-name">
                                            <i class="fas fa-capsules"></i>
                                            <?php echo htmlspecialchars($move->medicine_name); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($move->type == 'add'): ?>
                                            <span class="movement-badge add">
                                                <i class="fas fa-plus-circle"></i> إضافة
                                            </span>
                                        <?php else: ?>
                                            <span class="movement-badge remove">
                                                <i class="fas fa-minus-circle"></i> صرف
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="quantity-badge <?php echo $move->type; ?>">
                                            <?php echo $move->quantity; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-cell">
                                            <i class="fas fa-user-circle"></i>
                                            <?php echo htmlspecialchars($move->user_name); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($move->reference_type == 'prescription'): ?>
                                            <a href="<?php echo BASE_URL; ?>prescription/views/<?php echo $move->reference_id; ?>" class="reference-link">
                                                <i class="fas fa-prescription"></i> وصفة #<?php echo $move->reference_id; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="reference-text">-</span>
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
</div>

<script>
    document.getElementById('searchMovement').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('.movements-table tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if (text.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    .movements-table {
        width: 100%;
        border-collapse: collapse;
    }

    .movements-table th {
        background: #f8f9fa;
        padding: 12px;
        text-align: right;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .movements-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .datetime-cell {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #6c757d;
    }

    .movement-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .movement-badge.add {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .movement-badge.remove {
        background: #fee9ed;
        color: #dc3545;
    }

    .quantity-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .quantity-badge.add {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .quantity-badge.remove {
        background: #fee9ed;
        color: #dc3545;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .user-cell i {
        color: #0066cc;
    }

    .reference-link {
        color: #0066cc;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
    }

    .reference-link:hover {
        text-decoration: underline;
    }

    .movement-row.add {
        border-right: 3px solid #2e7d32;
    }

    .movement-row.remove {
        border-right: 3px solid #dc3545;
    }
</style>