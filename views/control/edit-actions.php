<!-- views/control/edit-actions.php -->
<div class="control-container">
    <div class="page-header">
        <h1>إدارة الإجراءات</h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Session::flash('success'); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>إضافة / تعديل إجراء</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/editActionsPost/<?php echo $type; ?>/<?php echo $id; ?>">
                <div class="form-group">
                    <label>اسم الإجراء</label>
                    <input type="text" name="action_name" required>
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">حفظ</button>
            </form>
        </div>
    </div>

    <!-- قائمة الإجراءات الحالية -->
    <div class="card">
        <div class="card-header">
            <h3>الإجراءات الحالية</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actions as $action): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($action->action_name); ?></td>
                            <td><?php echo htmlspecialchars($action->description); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $action->status == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo $action->status == 'active' ? 'نشط' : 'غير نشط'; ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($action->created_at)); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>control/editActions/type/<?php echo $action->id; ?>" class="btn-sm">تعديل</a>
                                <button onclick="deleteAction(<?php echo $action->id; ?>)" class="btn-sm btn-danger">حذف</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function deleteAction(id) {
        if (confirm('هل أنت متأكد من حذف هذا الإجراء؟')) {
            fetch('<?php echo BASE_URL; ?>control/deleteAction/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('خطأ: ' + data.error);
                    }
                });
        }
    }
</script>