<!-- views/control/edit-editor.php -->
<div class="control-container">
    <div class="page-header">
        <h1>تعديل المحرر</h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Session::flash('error'); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/editEditorPost/<?php echo $editor->id; ?>">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($editor->name); ?>" required>
                </div>
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($editor->email); ?>" required>
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($editor->phone); ?>">
                </div>
                <button type="submit" class="btn-submit">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</div>