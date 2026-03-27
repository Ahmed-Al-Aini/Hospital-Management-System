<!-- views/patient/create.php -->
<div class="patient-create-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>إضافة مريض جديد</h1>
            <p class="subtitle">إدخال بيانات مريض جديد في النظام</p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>

            <a href="<?php echo BASE_URL; ?>patient/list" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <!-- بطاقات إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo date('Y-m-d'); ?></h3>
                <p>تاريخ الإضافة</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="stat-details">
                <h3>رقم جديد</h3>
                <p>سيتم إنشاؤه تلقائياً</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3>+<?php echo $stats['today'] ?? 0; ?></h3>
                <p>مرضى جدد اليوم</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['total'] ?? 0; ?></h3>
                <p>إجمالي المرضى</p>
            </div>
        </div>
    </div>

    <!-- نموذج إضافة مريض -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> بيانات المريض</h3>
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

            <form method="POST" action="<?php echo BASE_URL; ?>patient/store" class="patient-form">
                <?php echo CSRF::field(); ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i>
                            الاسم الكامل
                            <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            placeholder="أدخل الاسم الكامل للمريض"
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                            required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="national_id">
                            <i class="fas fa-id-card"></i>
                            رقم الهوية
                        </label>
                        <input type="text" id="national_id" name="national_id"
                            placeholder="أدخل رقم الهوية (اختياري)"
                            value="<?php echo htmlspecialchars($_POST['national_id'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_date">
                            <i class="fas fa-birthday-cake"></i>
                            تاريخ الميلاد
                        </label>
                        <input type="date" id="birth_date" name="birth_date"
                            value="<?php echo htmlspecialchars($_POST['birth_date'] ?? ''); ?>">
                        <small class="form-hint">سيتم حساب العمر تلقائياً</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i>
                            رقم الهاتف
                            <span class="required">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone"
                            placeholder="05xxxxxxxx"
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">
                            <i class="fas fa-venus-mars"></i>
                            الجنس
                        </label>
                        <select id="gender" name="gender">
                            <option value="">-- اختر الجنس --</option>
                            <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : ''; ?>>
                                <i class="fas fa-mars"></i> ذكر
                            </option>
                            <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : ''; ?>>
                                <i class="fas fa-venus"></i> أنثى
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="blood_type">
                            <i class="fas fa-tint"></i>
                            فصيلة الدم
                        </label>
                        <select id="blood_type" name="blood_type">
                            <option value="">-- اختر فصيلة الدم --</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-map-marker-alt"></i>
                        العنوان
                    </label>
                    <textarea id="address" name="address" rows="2"
                        placeholder="أدخل العنوان الكامل (اختياري)"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ البيانات
                    </button>
                    <a href="<?php echo BASE_URL; ?>patient/list" class="btn-cancel">
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
            <h4>معلومات مهمة</h4>
            <p>بعد إضافة المريض، يمكنك إنشاء وصفة طبية له أو تسجيل زيارة جديدة.</p>
        </div>
    </div>
</div>

<style>
    /* ===== التصميم العام ===== */
    .patient-create-container {
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
    .patient-form {
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
        margin-bottom: 0;
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
    .form-group select,
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
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-group input.error,
    .form-group select.error,
    .form-group textarea.error {
        border-color: #dc3545;
    }

    .form-hint {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: flex-end;
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

    .alert-success {
        background: #e8f5e9;
        border-right: 4px solid #2e7d32;
        color: #1e5a1f;
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
    }

    /* ===== تصميم متجاوب ===== */
    @media (max-width: 768px) {
        .patient-create-container {
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