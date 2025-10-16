<?php $__env->startSection('title','Admin'); ?>

<?php $__env->startSection('content'); ?>
  <h4 class="mb-3">Trang Admin</h4>
  <div class="list-group">
    <a href="<?php echo e(route('admin.accounts.index')); ?>" class="list-group-item list-group-item-action">
      Danh sách tài khoản
    </a>
    
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/dash/admin.blade.php ENDPATH**/ ?>