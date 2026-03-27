</main> <!-- نهاية main-content -->
</div> <!-- نهاية wrapper -->

<!-- JavaScript -->
<script src="<?php echo JS_URL; ?>main.js"></script>
<script src="<?php echo JS_URL; ?>api.js"></script>

<?php if (Session::hasFlash('success')): ?>
    <script>
        alert('✅ <?php echo addslashes(Session::flash('success')); ?>');
    </script>
<?php endif; ?>

<?php if (Session::hasFlash('error')): ?>
    <script>
        alert('❌ <?php echo addslashes(Session::flash('error')); ?>');
    </script>
<?php endif; ?>

</body>

</html>