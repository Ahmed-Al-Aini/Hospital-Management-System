function dispensePrescription(id) {
    if (!confirm('هل أنت متأكد من صرف هذه الوصفة؟')) {
        return;
    }
    
    showLoading();
    
    fetch(`${BASE_URL}pharmacy/dispense/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('تم صرف الوصفة بنجاح', 'success');
            document.getElementById(`prescription-${id}`)?.remove();
        } else {
            showNotification('خطأ: ' + data.error, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

// تحديث قائمة الانتظار تلقائياً (اختياري)
setInterval(() => {
    if (window.location.pathname.includes('pharmacy/queue')) {
        location.reload();
    }
}, 60000); // كل دقيقة