<?php $hideSidebar = true; // نمنع ظهور sidebar في صفحة login 
?>

<div class="login-container">
    <div class="login-box">
        <h2><?php echo APP_NAME; ?></h2>
        <p>تسجيل الدخول إلى النظام</p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_URL; ?>auth/login">
            <div class="form-group">
                <?php  echo csrf_field();?>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">تسجيل الدخول</button>
        </form>
    </div>
</div>

<?php

//echo password_hash('admin', PASSWORD_BCRYPT, ['cost' => 12]);
?>