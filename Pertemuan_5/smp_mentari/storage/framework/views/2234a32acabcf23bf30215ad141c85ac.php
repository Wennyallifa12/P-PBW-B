<?php $__env->startSection('admin_title', 'Dashboard'); ?>

<?php $__env->startSection('admin_content'); ?>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Selamat Datang, <?php echo e(Auth::user()->name); ?>!</h3>
        <p>Ini adalah pusat kendali untuk website SMP Mentari. Silakan pilih menu di sebelah kiri untuk mulai mengelola konten.</p>

        

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\smpmentari\resources\views/dashboard.blade.php ENDPATH**/ ?>