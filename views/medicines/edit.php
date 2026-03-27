<!-- views/medicine/edit.php -->
<div class="medicine-edit-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>تعديل الدواء</h1>
            <p class="subtitle">تعديل بيانات الدواء: <?php echo htmlspecialchars($medicine->name); ?></p>
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

    <!-- بطاقات معلومات الدواء -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo htmlspecialchars($medicine->name); ?></h3>
                <p>اسم الدواء</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-barcode"></i>
            </div>
            <div class="stat-details">
                <h3>#<?php echo $medicine->id; ?></h3>
                <p>رقم المعرف</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $medicine->current_stock; ?></h3>
                <p>الكمية الحالية</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('Y-m-d', strtotime($medicine->created_at)); ?></h3>
                <p>تاريخ الإضافة</p>
            </div>
        </div>
    </div>

    <!-- نموذج تعديل الدواء -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> تعديل بيانات الدواء</h3>
            <span class="required-badge">* الحقول الإلزامية</span>
        </div>
        <div class="card-body">
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo Session::flash('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo Session::flash('success'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>medicine/update/<?php echo $medicine->id; ?>" class="medicine-form">
                <?php echo CSRF::field(); ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-capsules"></i>
                            اسم الدواء
                            <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            placeholder="أدخل اسم الدواء"
                            value="<?php echo htmlspecialchars($medicine->name); ?>"
                            required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i>
                        الوصف
                    </label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="أدخل وصفاً تفصيلياً للدواء"><?php echo htmlspecialchars($medicine->description ?? ''); ?></textarea>
                    <small class="form-hint">معلومات إضافية عن الدواء (الاستخدامات، التحذيرات، ...)</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="min_quantity">
                            <i class="fas fa-chart-line"></i>
                            الحد الأدنى للمخزون
                        </label>
                        <input type="number" id="min_quantity" name="min_quantity"
                            value="<?php echo $medicine->min_quantity; ?>"
                            min="1" class="quantity-input">
                        <small class="form-hint">عند وصول المخزون لهذا الرقم سيظهر تنبيه "منخفض"</small>
                    </div>

                    <div class="form-group">
                        <label for="critical_quantity">
                            <i class="fas fa-exclamation-triangle"></i>
                            الحد الحرج
                        </label>
                        <input type="number" id="critical_quantity" name="critical_quantity"
                            value="<?php echo $medicine->critical_quantity; ?>"
                            min="1" class="quantity-input">
                        <small class="form-hint">عند وصول المخزون لهذا الرقم سيظهر تنبيه "حرج"</small>
                    </div>
                </div>

                <div class="form-info">
                    <div class="info-icon-small">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="info-text">
                        <strong>نصيحة:</strong> الحد الحرج يجب أن يكون أقل من الحد الأدنى.
                        <?php if ($medicine->critical_quantity >= $medicine->min_quantity): ?>
                            <span class="warning-text">⚠️ القيم الحالية غير صحيحة! الحد الحرج يجب أن يكون أقل من الحد الأدنى.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                    <a href="<?php echo BASE_URL; ?>medicine/list" class="btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                    <button type="button" onclick="showAddStock(<?php echo $medicine->id; ?>)" class="btn-add-stock">
                        <i class="fas fa-plus-circle"></i> إضافة مخزون
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-content">
            <h4>معلومات مهمة</h4>
            <p>• يمكنك تعديل الحد الأدنى والحد الحرج حسب احتياجات المخزون<br>
                • يمكنك إضافة مخزون جديد من خلال زر "إضافة مخزون"<br>
                • تغيير اسم الدواء سيؤثر على جميع الوصفات المرتبطة به</p>
        </div>
    </div>
</div>

<!-- Modal لإضافة المخزون -->
<div id="addStockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> إضافة مخزون</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addStockForm" method="POST" action="">
                <?php echo CSRF::field(); ?>
                <div class="form-group">
                    <label for="quantity">
                        <i class="fas fa-boxes"></i>
                        الكمية
                    </label>
                    <input type="number" id="quantity" name="quantity" min="1" required placeholder="أدخل الكمية">
                </div>
                <div class="form-group">
                    <label for="expiry_date">
                        <i class="fas fa-calendar-alt"></i>
                        تاريخ انتهاء الصلاحية
                    </label>
                    <input type="date" id="expiry_date" name="expiry_date" required>
                </div>
                <div class="form-actions-modal">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> إضافة
                    </button>
                    <button type="button" class="btn-cancel-modal" onclick="closeModal()">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // عرض مودال إضافة المخزون
    function showAddStock(medicineId) {
        const modal = document.getElementById('addStockModal');
        const form = document.getElementById('addStockForm');
        form.action = '<?php echo BASE_URL; ?>medicine/addStock/' + medicineId;
        modal.style.display = 'flex';
    }

    // إغلاق المودال
    function closeModal() {
        document.getElementById('addStockModal').style.display = 'none';
    }

    // إغلاق المودال عند الضغط خارجها
    window.onclick = function(event) {
        const modal = document.getElementById('addStockModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // إغلاق المودال بالضغط على Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // التحقق من صحة المدخلات قبل الإرسال
    document.querySelector('.medicine-form').addEventListener('submit', function(e) {
        const minQuantity = parseInt(document.getElementById('min_quantity').value);
        const criticalQuantity = parseInt(document.getElementById('critical_quantity').value);

        if (criticalQuantity >= minQuantity) {
            e.preventDefault();
            alert('⚠️ الحد الحرج يجب أن يكون أقل من الحد الأدنى للمخزون');
            return false;
        }

        const name = document.getElementById('name').value.trim();
        if (name === '') {
            e.preventDefault();
            alert('⚠️ اسم الدواء مطلوب');
            return false;
        }

        return true;
    });
</script>

<style>
    /* ===== التصميم العام ===== */
    .medicine-edit-container {
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
        font-size: 18px;
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

    .required-badge {
        background: #fee9ed;
        color: #dc3545;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .card-body {
        padding: 25px;
    }

    /* ===== النموذج ===== */
    .medicine-form {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1e2b37;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group label i {
        color: #0066cc;
        margin-left: 5px;
    }

    .form-group .required {
        color: #dc3545;
        margin-right: 3px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .quantity-input {
        text-align: center;
        font-weight: 500;
    }

    .form-hint {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    /* ===== معلومات إضافية داخل النموذج ===== */
    .form-info {
        background: #e3f2fd;
        border-radius: 8px;
        padding: 12px 15px;
        margin: 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-icon-small {
        width: 30px;
        height: 30px;
        background: #0066cc;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-icon-small i {
        color: white;
        font-size: 14px;
    }

    .info-text {
        flex: 1;
        font-size: 13px;
        color: #0066cc;
    }

    .info-text strong {
        color: #1e2b37;
    }

    .warning-text {
        color: #dc3545;
        display: block;
        margin-top: 5px;
    }

    /* ===== أزرار الإجراءات ===== */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-submit {
        background: #0066cc;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #0052a3;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #6c757d;
        padding: 12px 24px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #e9ecef;
        color: #1e2b37;
    }

    .btn-add-stock {
        background: #28a745;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-add-stock:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    /* ===== التنبيهات ===== */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-error {
        background: #fee9ed;
        border-right: 4px solid #dc3545;
        color: #b71c3a;
    }

    .alert-success {
        background: #e8f5e9;
        border-right: 4px solid #2e7d32;
        color: #1e5a1f;
    }

    .alert i {
        font-size: 20px;
    }

    /* ===== المودال ===== */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header {
        padding: 20px 25px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: #1e2b37;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-header h3 i {
        color: #0066cc;
    }

    .modal-header .close {
        font-size: 28px;
        cursor: pointer;
        color: #6c757d;
        transition: all 0.3s;
    }

    .modal-header .close:hover {
        color: #dc3545;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-body .form-group {
        margin-bottom: 20px;
    }

    .modal-body label {
        display: block;
        margin-bottom: 8px;
        color: #1e2b37;
        font-weight: 500;
        font-size: 14px;
    }

    .modal-body input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
    }

    .modal-body input:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-actions-modal {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-cancel-modal {
        background: #f8f9fa;
        color: #6c757d;
        padding: 10px 20px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        flex: 1;
    }

    .btn-cancel-modal:hover {
        background: #e9ecef;
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
    @media (max-width: 768px) {
        .medicine-edit-container {
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

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel,
        .btn-add-stock {
            width: 100%;
            justify-content: center;
        }

        .info-card {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-body {
            padding: 20px;
        }
    }
</style>