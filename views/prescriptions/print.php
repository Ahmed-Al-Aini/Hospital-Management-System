<?php $hideSidebar = true;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>وصفة طبية</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 30px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .medicines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .medicines-table th,
        .medicines-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .medicines-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
            text-align: left;
        }

        .signature {
            margin-top: 30px;
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button class="btn btn-secondary" onclick="window.print()">طباعة</button>
        <button class="btn btn-danger" onclick="window.close()">إغلاق</button>
    </div>

    <div class="header">
        <h1><?php echo APP_NAME; ?></h1>
        <h2>وصفة طبية</h2>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>المريض:</strong> <?php echo htmlspecialchars($prescription->patient_name); ?></td>
            <td><strong>رقم الهوية:</strong> <?php echo htmlspecialchars($prescription->patient_national_id ?? '-'); ?></td>
        </tr>
        <tr>
            <td><strong>الطبيب:</strong> <?php echo htmlspecialchars($prescription->doctor_name); ?></td>
            <td><strong>التاريخ:</strong> <?php echo date('Y-m-d', strtotime($prescription->created_at)); ?></td>
        </tr>
    </table>

    <h3>الأدوية الموصوفة</h3>
    <table class="medicines-table">
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

    <div class="footer">
        <div class="signature">
            توقيع الطبيب
        </div>
    </div>
</body>

</html>