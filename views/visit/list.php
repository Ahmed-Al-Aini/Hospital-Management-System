<!-- views/visit/list.php -->
<div class="visits-container">
    <div class="page-header">
        <div>
            <h1>سجل الزيارات</h1>
            <p class="subtitle">عرض جميع زيارات المرضى</p>
        </div>
        <div class="header-date">
            <i class="fas fa-calendar-alt"></i>
            <span><?php echo date('Y-m-d'); ?></span>
        </div>
    </div>

    <!-- أزرار التصفية -->
    <div class="filter-tabs">
        <a href="<?php echo BASE_URL; ?>visit/list?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> الكل
        </a>
        <a href="<?php echo BASE_URL; ?>visit/list?filter=today" class="filter-btn <?php echo $filter == 'today' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-day"></i> اليوم
        </a>
        <a href="<?php echo BASE_URL; ?>visit/list?filter=my" class="filter-btn <?php echo $filter == 'my' ? 'active' : ''; ?>">
            <i class="fas fa-user-md"></i> زياراتي
        </a>
    </div>

    <!-- قائمة الزيارات -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> قائمة الزيارات</h3>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchVisit" placeholder="بحث...">
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($visits)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>لا توجد زيارات</p>
                </div>
            <?php else: ?>
                <div class="visits-list" id="visitsList">
                    <?php foreach ($visits as $visit): ?>
                        <div class="visit-item">
                            <div class="visit-date-badge">
                                <span class="day"><?php echo date('d', strtotime($visit->visit_date)); ?></span>
                                <span class="month"><?php echo date('M', strtotime($visit->visit_date)); ?></span>
                            </div>
                            <div class="visit-info">
                                <h4>
                                    <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $visit->patient_id; ?>">
                                        <?php echo htmlspecialchars($visit->patient_name); ?>
                                    </a>
                                </h4>
                                <p class="visit-meta">
                                    <i class="fas fa-user-md"></i> د. <?php echo htmlspecialchars($visit->doctor_name); ?> |
                                    <i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($visit->visit_date)); ?>
                                </p>
                                <?php if ($visit->diagnosis): ?>
                                    <p class="visit-diagnosis">
                                        <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($visit->diagnosis); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="visit-actions">
                                <a href="<?php echo BASE_URL; ?>visit/views/<?php echo $visit->id; ?>" class="btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchVisit')?.addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let items = document.querySelectorAll('#visitsList .visit-item');

        items.forEach(item => {
            let text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
</script>

<style>
    .visits-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 25px;
        background: #f8f9fa;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1e2b37;
        margin: 0 0 5px 0;
    }

    .subtitle {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
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

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
    }

    .filter-btn {
        padding: 10px 20px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: #1e2b37;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .filter-btn.active {
        background: #0066cc;
        color: white;
        border-color: #0066cc;
    }

    .filter-btn:hover:not(.active) {
        background: #f8f9fa;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
    }

    .card-body {
        padding: 25px;
    }

    .visits-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .visit-item {
        display: flex;
        align-items: center;
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

    .visit-date-badge {
        min-width: 60px;
        text-align: center;
        background: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .visit-date-badge .day {
        display: block;
        font-size: 20px;
        font-weight: 700;
        color: #0066cc;
        line-height: 1;
    }

    .visit-date-badge .month {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }

    .visit-info {
        flex: 1;
    }

    .visit-info h4 {
        margin: 0 0 5px 0;
    }

    .visit-info h4 a {
        color: #1e2b37;
        text-decoration: none;
    }

    .visit-info h4 a:hover {
        color: #0066cc;
    }

    .visit-meta {
        color: #6c757d;
        font-size: 13px;
        margin: 0 0 5px 0;
    }

    .visit-meta i {
        color: #0066cc;
    }

    .visit-diagnosis {
        color: #1e2b37;
        font-size: 14px;
        margin: 0;
    }

    .visit-actions .btn-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: white;
        color: #6c757d;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .visit-actions .btn-view:hover {
        background: #0066cc;
        color: white;
    }

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

    @media (max-width: 768px) {
        .visit-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .visit-actions {
            align-self: flex-end;
        }

        .filter-tabs {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>