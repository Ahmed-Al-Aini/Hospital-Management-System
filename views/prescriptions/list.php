<div class="page-header">
    <h1>الوصفات الطبية</h1>
    <?php if (Auth::has_role(ROLE_DOCTOR)): ?>
        <a href="<?php echo BASE_URL; ?>prescription/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> إنشاء وصفة
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>الطبيب</th>
                    <th>تاريخ الإنشاء</th>
                    <th>عدد الأدوية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prescriptions as $index => $p): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($p->patient_name); ?></td>
                        <td><?php echo htmlspecialchars($p->doctor_name); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($p->created_at)); ?></td>
                        <td><?php echo $p->items_count; ?></td>
                        <td>
                            <?php if ($p->status == STATUS_PENDING): ?>
                                <span class="badge badge-warning">قيد الانتظار</span>
                            <?php elseif ($p->status == STATUS_DISPENSED): ?>
                                <span class="badge badge-success">تم الصرف</span>
                            <?php elseif ($p->status == STATUS_CANCELLED): ?>
                                <span class="badge badge-danger">ملغية</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>prescription/views/<?php echo $p->id; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                            <?php if ($p->status == STATUS_PENDING && Auth::has_role(ROLE_PHARMACIST)): ?>
                                <button onclick="dispensePrescription(<?php echo $p->id; ?>)" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> صرف
                                </button>
                            <?php endif; ?>
                            <?php if (Auth::hasAnyRole([ROLE_ADMIN, ROLE_DOCTOR])): ?>
                                <a href="<?php echo BASE_URL; ?>prescription/print/<?php echo $p->id; ?>" class="btn btn-sm btn-secondary" target="_blank">
                                    <i class="fas fa-print"></i> طباعة
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function dispensePrescription(id) {
        if (!confirm('هل أنت متأكد من صرف هذه الوصفة؟')) {
            return;
        }

        fetch('<?php echo BASE_URL; ?>pharmacy/dispense/' + id, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                    showNotification('تم صرف الوصفة بنجاح');
                } else {
                    showNotification('خطأ: ' + data.error, 'error');
                }
            });
    }
</script>