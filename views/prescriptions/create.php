<!-- views/prescriptions/create.php -->
<div class="prescription-create-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>إنشاء وصفة جديدة</h1>
            <p class="subtitle">إضافة وصفة طبية جديدة للمريض</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>

            <?php
            $backUrl = BASE_URL . 'prescription/list';
            if (isset($_GET['patient_id'])) {
                $backUrl = BASE_URL . 'patient/views/' . $_GET['patient_id'];
            }
            ?>
            <a href="<?php echo $backUrl; ?>" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <!-- بطاقات إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-details">
                <h3>وصفة جديدة</h3>
                <p>رقم الوصفة سيتم إنشاؤه تلقائياً</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'الطبيب'); ?></h3>
                <p>الطبيب المعالج</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('Y-m-d'); ?></h3>
                <p>تاريخ الوصفة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($medicines); ?></h3>
                <p>الأدوية المتاحة</p>
            </div>
        </div>
    </div>

    <!-- نموذج إنشاء الوصفة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-file-prescription"></i> بيانات الوصفة</h3>
            <span class="required-badge">* الحقول الإلزامية</span>
        </div>
        <div class="card-body">
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo Session::flash('error'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>prescription/store" id="prescriptionForm" class="prescription-form">
                <?php echo CSRF::field(); ?>

                <!-- اختيار المريض -->
                <div class="form-group">
                    <label for="patient_id">
                        <i class="fas fa-user"></i>
                        المريض
                        <span class="required">*</span>
                    </label>
                    <select id="patient_id" name="patient_id" class="patient-select" required>
                        <option value="">-- اختر مريض --</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?php echo $patient->id; ?>"
                                <?php echo (isset($_GET['patient_id']) && $_GET['patient_id'] == $patient->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($patient->name); ?>
                                <?php if ($patient->national_id): ?>
                                    (<?php echo htmlspecialchars($patient->national_id); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- قسم الأدوية -->
                <div class="medicines-section">
                    <h4><i class="fas fa-pills"></i> الأدوية الموصوفة</h4>
                    <div id="medicines-container">
                        <div class="medicine-item">
                            <div class="medicine-row">
                                <div class="medicine-field">
                                    <label>الدواء</label>
                                    <select name="items[0][medicine_id]" class="medicine-select" required>
                                        <option value="">-- اختر دواء --</option>
                                        <?php foreach ($medicines as $medicine): ?>
                                            <option value="<?php echo $medicine->id; ?>"
                                                data-stock="<?php echo $medicine->current_stock ?? 0; ?>"
                                                data-name="<?php echo htmlspecialchars($medicine->name); ?>">
                                                <?php echo htmlspecialchars($medicine->name); ?>
                                                (المتوفر: <?php echo $medicine->current_stock ?? 0; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="medicine-field">
                                    <label>الجرعة</label>
                                    <input type="text" name="items[0][dosage]" placeholder="مثال: 500mg" class="dosage-input">
                                </div>
                                <div class="medicine-field">
                                    <label>الكمية</label>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" class="quantity-input">
                                </div>
                                <div class="medicine-field">
                                    <label>تعليمات</label>
                                    <input type="text" name="items[0][instructions]" placeholder="مثال: بعد الأكل">
                                </div>
                                <div class="medicine-field action-field">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn-remove remove-medicine">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addMedicine" class="btn-add-medicine">
                        <i class="fas fa-plus"></i> إضافة دواء
                    </button>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ الوصفة
                    </button>
                    <a href="<?php echo $backUrl; ?>" class="btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
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
            <h4>ملاحظات مهمة</h4>
            <p>• تأكد من توفر الأدوية الموصوفة في المخزون<br>
                • يمكن إضافة أكثر من دواء في الوصفة الواحدة<br>
                • الوصفة ستكون قيد الانتظار حتى يتم صرفها من الصيدلية</p>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;

    document.getElementById('addMedicine').addEventListener('click', function() {
        const container = document.getElementById('medicines-container');
        const newItem = document.createElement('div');
        newItem.className = 'medicine-item';
        newItem.innerHTML = `
            <div class="medicine-row">
                <div class="medicine-field">
                    <label>الدواء</label>
                    <select name="items[${itemIndex}][medicine_id]" class="medicine-select" required>
                        <option value="">-- اختر دواء --</option>
                        <?php foreach ($medicines as $medicine): ?>
                        <option value="<?php echo $medicine->id; ?>" data-stock="<?php echo $medicine->current_stock ?? 0; ?>">
                            <?php echo htmlspecialchars($medicine->name); ?> (المتوفر: <?php echo $medicine->current_stock ?? 0; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="medicine-field">
                    <label>الجرعة</label>
                    <input type="text" name="items[${itemIndex}][dosage]" placeholder="مثال: 500mg">
                </div>
                <div class="medicine-field">
                    <label>الكمية</label>
                    <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="quantity-input">
                </div>
                <div class="medicine-field">
                    <label>تعليمات</label>
                    <input type="text" name="items[${itemIndex}][instructions]" placeholder="مثال: بعد الأكل">
                </div>
                <div class="medicine-field action-field">
                    <label>&nbsp;</label>
                    <button type="button" class="btn-remove remove-medicine">
                        <i class="fas fa-trash-alt"></i> حذف
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-medicine') || e.target.parentElement.classList.contains('remove-medicine')) {
            const btn = e.target.classList.contains('remove-medicine') ? e.target : e.target.parentElement;
            btn.closest('.medicine-item').remove();
        }
    });

    // التحقق من توفر الدواء عند تغيير الكمية
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.medicine-row');
            const select = row.querySelector('.medicine-select');
            const selectedOption = select.options[select.selectedIndex];
            const availableStock = parseInt(selectedOption.dataset.stock || 0);
            const quantity = parseInt(e.target.value || 0);

            if (quantity > availableStock && availableStock > 0) {
                alert(`تنبيه: الكمية المطلوبة (${quantity}) أكبر من المتوفر (${availableStock})`);
            }
        }
    });
</script>

<style>
    /* ===== التصميم العام ===== */
    .prescription-create-container {
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
    .prescription-form {
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 25px;
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

    .patient-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .patient-select:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    /* ===== قسم الأدوية ===== */
    .medicines-section {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .medicines-section h4 {
        margin: 0 0 20px 0;
        color: #1e2b37;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .medicines-section h4 i {
        color: #0066cc;
    }

    #medicines-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .medicine-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border-right: 3px solid #0066cc;
    }

    .medicine-row {
        display: grid;
        grid-template-columns: 1fr 0.8fr 0.5fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .medicine-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .medicine-field label {
        font-size: 12px;
        font-weight: 500;
        color: #6c757d;
        margin: 0;
    }

    .medicine-select,
    .medicine-field input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .medicine-select:focus,
    .medicine-field input:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.1);
    }

    .action-field {
        justify-content: flex-end;
    }

    .btn-remove {
        background: #fee9ed;
        color: #dc3545;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
        width: 100%;
        justify-content: center;
    }

    .btn-remove:hover {
        background: #dc3545;
        color: white;
    }

    .btn-add-medicine {
        background: #e8f5e9;
        color: #2e7d32;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-add-medicine:hover {
        background: #2e7d32;
        color: white;
        transform: translateY(-2px);
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
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #0052a3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #6c757d;
        padding: 12px 30px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-decoration: none;
        font-size: 16px;
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

    .alert i {
        font-size: 20px;
    }

    /* ===== بطاقة معلومات ===== */
    .info-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdef5 100%);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
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
    @media (max-width: 992px) {
        .medicine-row {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .action-field {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {
        .prescription-create-container {
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

        .medicine-row {
            grid-template-columns: 1fr;
        }

        .action-field {
            grid-column: span 1;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
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