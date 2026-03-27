<!-- views/control/change-activity.php -->
<div class="control-container">
    <div class="page-header">
        <h1>تغيير النشاط</h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/changeActivityPost">
                <div class="form-group">
                    <label>اختر المحرر</label>
                    <select name="editor_id" required>
                        <option value="">-- اختر --</option>
                        <?php foreach ($editors as $editor): ?>
                            <option value="<?php echo $editor->id; ?>">
                                <?php echo htmlspecialchars($editor->name); ?>
                                (الحالي: <?php echo $editor->is_active ? 'نشط' : 'غير نشط'; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>الحالة الجديدة</label>
                    <select name="status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">تغيير الحالة</button>
            </form>
        </div>
    </div>
</div>