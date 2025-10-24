<?php $__env->startSection('title', 'Selamat Datang di SMP Mentari'); ?>

<?php $__env->startSection('content'); ?>
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Kegiatan Terbaru di SMP Mentari</h1>
        <p class="text-lg text-gray-600 mt-2">Selamat datang di website resmi kami. Berikut adalah beberapa kegiatan yang telah kami laksanakan.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php $__empty_1 = true; $__currentLoopData = $kegiatan_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-2"><?php echo e($kegiatan->judul); ?></h3>
                <p class="text-gray-700"><?php echo e($kegiatan->deskripsi); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-8">
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative inline-block" role="alert">
                    <p class="font-bold">Belum Ada Kegiatan</p>
                    <p class="text-sm">Belum ada kegiatan yang dipublikasikan. Silakan cek kembali nanti.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($kegiatan_terbaru->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($kegiatan_terbaru->links()); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\smpmentari\resources\views/home.blade.php ENDPATH**/ ?>