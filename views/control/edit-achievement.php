<!-- views/control/edit-achievement.php -->
<div class="control-container">
    <div class="page-header">
        <h1><?php echo $id ? 'تعديل الإنجاز' : 'إضافة إنجاز جديد'; ?></h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/editAchievementPost/<?php echo $id; ?>">
                <div class="form-group">
                    <label>عنوان الإنجاز</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($achievement->title ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" rows="3"><?php echo htmlspecialchars($achievement->description ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>اختر المستخدم</label>
                    <select name="user_id" required>
                        <option value="">-- اختر --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user->id; ?>" <?php echo ($achievement->user_id ?? 0) == $user->id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>تاريخ الإنجاز</label>
                    <input type="date" name="achievement_date" value="<?php echo $achievement->achievement_date ?? date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="pending" <?php echo ($achievement->status ?? '') == 'pending' ? 'selected' : ''; ?>>قيد الانتظار</option>
                        <option value="completed" <?php echo ($achievement->status ?? '') == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                        <option value="cancelled" <?php echo ($achievement->status ?? '') == 'cancelled' ? 'selected' : ''; ?>>ملغي</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" rows="2"><?php echo htmlspecialchars($achievement->notes ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn-submit">حفظ</button>
            </form>
        </div>
    </div>
</div>