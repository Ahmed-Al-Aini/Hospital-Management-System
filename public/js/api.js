class API {
    static async get(url) {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        return response.json();
    }

    static async post(url, data) {

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken || ''
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }
    
    static async searchMedicines(query) {
        return this.get(`${BASE_URL}api/searchMedicines?q=${encodeURIComponent(query)}`);
    }
    
    static async checkMedicine(medicineId, quantity) {
        return this.post(`${BASE_URL}api/checkMedicine`, {
            medicine_id: medicineId,
            quantity: quantity
        });
    }
    
    static async getNotifications() {
        return this.get(`${BASE_URL}api/getNotifications`);
    }
}

// التحقق من الأدوية عند إدخال الكمية
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        const medicineSelect = e.target.closest('.medicine-item').querySelector('.medicine-select');
        if (medicineSelect && medicineSelect.value) {
            const medicineId = medicineSelect.value;
            const quantity = e.target.value;
            
            API.checkMedicine(medicineId, quantity).then(data => {
                if (!data.available) {
                    showNotification(`تنبيه: ${data.medicine_name} المتوفر فقط ${data.current_stock}`, 'warning');
                }
            });
        }
    }
});

// استطلاع التنبيهات كل 30 ثانية
setInterval(async () => {
    if (typeof BASE_URL !== 'undefined') {
        const data = await API.getNotifications();
        if (data.count > 0) {
            // يمكن تحديث شارة التنبيهات هنا
            console.log('لديك', data.count, 'تنبيهات جديدة');
        }
    }
}, 30000);