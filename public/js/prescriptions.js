let medicineIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addMedicine');
    if (addBtn) {
        addBtn.addEventListener('click', addMedicineRow);
    }
    
    // حذف صف دواء
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-medicine')) {
            e.target.closest('.medicine-item').remove();
        }
    });
});

function addMedicineRow() {
    const container = document.getElementById('medicines-container');
    const template = document.getElementById('medicine-template').innerHTML;
    const newRow = template.replace(/INDEX/g, medicineIndex);
    container.insertAdjacentHTML('beforeend', newRow);
    medicineIndex++;
}

// التحقق من صحة النموذج قبل الإرسال
function validatePrescriptionForm() {
    const patientId = document.getElementById('patient_id').value;
    if (!patientId) {
        alert('الرجاء اختيار مريض');
        return false;
    }
    
    const medicineRows = document.querySelectorAll('.medicine-item');
    if (medicineRows.length === 0) {
        alert('الرجاء إضافة دواء واحد على الأقل');
        return false;
    }
    
    for (let row of medicineRows) {
        const select = row.querySelector('.medicine-select');
        if (!select.value) {
            alert('الرجاء اختيار دواء لجميع الصفوف');
            return false;
        }
    }
    
    return true;
}