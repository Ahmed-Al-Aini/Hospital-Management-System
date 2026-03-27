<!-- views/control/edit-production.php -->
<div class="control-container">
    <div class="page-header">
        <h1><?php echo $id ? 'تعديل الإنتاج' : 'إضافة إنتاج جديد'; ?></h1>
        <a href="<?php echo BASE_URL; ?>dashboard" class="btn-back">العودة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>control/editProductionPost/<?php echo $id; ?>">
                <div class="form-group">
                    <label>اسم المنتج</label>
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($production->product_name ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>الكمية</label>
                    <input type="number" name="quantity" value="<?php echo $production->quantity ?? 0; ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label>الوحدة</label>
                    <select name="unit">
                        <option value="قطعة" <?php echo ($production->unit ?? '') == 'قطعة' ? 'selected' : ''; ?>>قطعة</option>
                        <option value="علبة" <?php echo ($production->unit ?? '') == 'علبة' ? 'selected' : ''; ?>>علبة</option>
                        <option value="شريط" <?php echo ($production->unit ?? '') == 'شريط' ? 'selected' : ''; ?>>شريط</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>تاريخ الإنتاج</label>
                    <input type="date" name="production_date" value="<?php echo $production->production_date ?? date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" rows="2"><?php echo htmlspecialchars($production->notes ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn-submit">حفظ</button>
            </form>
        </div>
    </div>
</div>