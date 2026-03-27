<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - غير مصرح بالوصول</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-container {
            text-align: center;
            background: white;
            padding: 50px 60px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: 20px;
        }

        .error-code {
            font-size: 100px;
            font-weight: 800;
            color: #dc3545;
            margin: 0;
            line-height: 1;
        }

        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin: 20px 0;
        }

        .error-message {
            font-size: 28px;
            color: #1e2b37;
            margin: 20px 0 10px;
        }

        .error-description {
            color: #6c757d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-home {
            background: #0066cc;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-home:hover {
            background: #0052a3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 102, 204, 0.3);
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h2 class="error-message">غير مصرح بالوصول</h2>
        <p class="error-description">
            <?php echo isset($message) ? htmlspecialchars($message) : 'ليس لديك الصلاحية الكافية للوصول إلى هذه الصفحة.'; ?>
        </p>
        <div class="buttons">
            <a href="javascript:history.back()" class="btn-back">العودة للخلف</a>
            <a href="<?php echo BASE_URL; ?>dashboard" class="btn-home">الذهاب للرئيسية</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>