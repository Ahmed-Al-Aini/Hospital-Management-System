<div class="page-header">
    <h1>تفاصيل الوصفة الطبية</h1>
    <div>
        <a href="<?php echo BASE_URL; ?>prescription/list" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> عودة
        </a>
        <?php if ($prescription->status == STATUS_PENDING && Auth::has_role(ROLE_PHARMACIST)): ?>
            <button onclick="dispensePrescription(<?php echo $prescription->id; ?>)" class="btn btn-success">
                <i class="fas fa-check"></i> صرف الوصفة
            </button>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>prescription/print/<?php echo $prescription->id; ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print"></i> طباعة
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h3>معلومات المريض</h3>
        <table class="table details-table">
            <tr>
                <th width="200">الاسم</th>
                <td><?php echo htmlspecialchars($prescription->patient_name); ?></td>
            </tr>
            <tr>
                <th>رقم الهوية</th>
                <td><?php echo htmlspecialchars($prescription->patient_national_id ?? '-'); ?></td>
            </tr>
        </table>

        <h3>معلومات الوصفة</h3>
        <table class="table details-table">
            <tr>
                <th width="200">الطبيب</th>
                <td><?php echo htmlspecialchars($prescription->doctor_name); ?></td>
            </tr>
            <tr>
                <th>تاريخ الإنشاء</th>
                <td><?php echo date('Y-m-d H:i', strtotime($prescription->created_at)); ?></td>
            </tr>
            <tr>
                <th>الحالة</th>
                <td>
                    <?php if ($prescription->status == STATUS_PENDING): ?>
                        <span class="badge badge-warning">قيد الانتظار</span>
                    <?php elseif ($prescription->status == STATUS_DISPENSED): ?>
                        <span class="badge badge-success">تم الصرف</span>
                        <br>
                        <small>بواسطة: <?php echo htmlspecialchars($prescription->dispensed_by_name); ?> في <?php echo date('Y-m-d H:i', strtotime($prescription->dispensed_at)); ?></small>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <h3>الأدوية</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الدواء</th>
                    <th>الجرعة</th>
                    <th>الكمية</th>
                    <th>التعليمات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prescription->items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->medicine_name); ?></td>
                        <td><?php echo htmlspecialchars($item->dosage ?? '-'); ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo htmlspecialchars($item->instructions ?? '-'); ?></td>
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

        fetch('<?php echo BASE_URL; ?>pharmacy/dispense/' + id , {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN':'<?php echo csrf_token()?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم صرف الوصفة بنجاح');
                    location.reload();
                } else {
                    alert('خطأ: ' + data.error);
                }
            });
    }
</script>