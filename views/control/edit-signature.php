<!-- views/control/edit-signature.php -->
<div class="control-container">
    <div class="page-header">
        <h1>تعديل التوقيع</h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/editSignaturePost">
                <div class="form-group">
                    <label>اختر المحرر</label>
                    <select name="editor_id" required>
                        <option value="">-- اختر --</option>
                        <?php foreach ($editors as $editor): ?>
                            <option value="<?php echo $editor->id; ?>">
                                <?php echo htmlspecialchars($editor->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>التوقيع الإلكتروني</label>
                    <textarea name="signature" rows="5" placeholder="أدخل التوقيع..."></textarea>
                </div>
                <button type="submit" class="btn-submit">حفظ التوقيع</button>
            </form>
        </div>
    </div>
</div>