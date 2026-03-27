<!-- views/control/update-editor.php -->
<div class="control-container">
    <div class="page-header">
        <h1>إضافة محرر جديد</h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة للوحة التحكم</a>
    </div>

    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Session::flash('error'); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/updateEditorPost">
                <div class="form-group">
                    <label>الاسم الكامل <span class="required">*</span></label>
                    <input type="text" name="name" required placeholder="أدخل الاسم الكامل">
                </div>
                <div class="form-group">
                    <label>البريد الإلكتروني <span class="required">*</span></label>
                    <input type="email" name="email" required placeholder="example@domain.com">
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" placeholder="05xxxxxxxx">
                </div>
                <div class="form-group">
                    <label>الصلاحية</label>
                    <select name="role">
                        <option value="editor">محرر</option>
                        <option value="admin">مدير</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">إضافة المحرر</button>
            </form>
        </div>
    </div>
</div>