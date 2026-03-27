<!-- views/visit/create.php -->
<div class="visit-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1>تسجيل زيارة جديدة</h1>
            <p class="subtitle">تسجيل زيارة للمريض: <?php echo htmlspecialchars($patient->name); ?></p>
        </div>

        <div class="header-actions">
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo date('Y-m-d'); ?></span>
            </div>
            <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $patient->id; ?>" class="btn-back">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <!-- معلومات المريض السريعة -->
    <div class="patient-info-card">
        <div class="patient-avatar">
            <i class="fas fa-user-circle fa-3x"></i>
        </div>
        <div class="patient-details">
            <h3><?php echo htmlspecialchars($patient->name); ?></h3>
            <p>
                <i class="fas fa-id-card"></i> <?php echo htmlspecialchars($patient->national_id ?? 'لا يوجد'); ?> |
                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($patient->phone ?? 'لا يوجد'); ?> |
                <i class="fas fa-<?php echo $patient->gender == 'male' ? 'mars' : 'venus'; ?>"></i>
                <?php echo $patient->gender == 'male' ? 'ذكر' : 'أنثى'; ?>
            </p>
        </div>
    </div>

    <!-- نموذج تسجيل الزيارة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-notes-medical"></i> تفاصيل الزيارة</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>visit/store">
                <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                <div class="form-group">
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> تاريخ الزيارة</label>
                        <input type="datetime-local" name="visit_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-head-side-cough"></i> الشكوى الرئيسية</label>
                    <textarea name="complaint" rows="3" placeholder="ما هي شكوى المريض؟" required></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> التشخيص</label>
                    <textarea name="diagnosis" rows="4" placeholder="التشخيص الطبي..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-notes-medical"></i> ملاحظات إضافية</label>
                    <textarea name="notes" rows="3" placeholder="أي ملاحظات أخرى..."></textarea>
                </div>

                <div class="form-actions">
                    <label class="checkbox-label">
                        <input type="checkbox" name="create_prescription" value="1">
                        <i class="fas fa-prescription"></i> إنشاء وصفة طبية بعد الحفظ
                    </label>

                    <div class="buttons">
                        <a href="<?php echo BASE_URL; ?>patient/views/<?php echo $patient->id; ?>" class="btn-cancel">
                            إلغاء
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> حفظ الزيارة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .visit-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f9fa;
    }

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

    .patient-info-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .patient-avatar i {
        color: #0066cc;
    }

    .patient-details h3 {
        margin: 0 0 5px 0;
        color: #1e2b37;
    }

    .patient-details p {
        margin: 0;
        color: #6c757d;
    }

    .patient-details i {
        color: #0066cc;
        margin-left: 5px;
    }

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

    .card-body {
        padding: 25px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
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
    }

    .form-group label i {
        color: #0066cc;
        margin-left: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: #1e2b37;
    }

    .checkbox-label i {
        color: #0066cc;
    }

    .checkbox-label input[type="checkbox"] {
        width: auto;
        margin-left: 5px;
    }

    .buttons {
        display: flex;
        gap: 10px;
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #6c757d;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        border: 1px solid #dee2e6;
    }

    .btn-cancel:hover {
        background: #e9ecef;
    }

    .btn-submit {
        background: #0066cc;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        background: #0052a3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
    }

    @media (max-width: 768px) {
        .visit-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .patient-info-card {
            flex-direction: column;
            text-align: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .buttons {
            width: 100%;
            flex-direction: column;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>