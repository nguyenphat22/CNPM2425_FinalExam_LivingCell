<?php $__env->startSection('title','Đổi mật khẩu'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">ĐỔI MẬT KHẨU</div>
      <div class="card-body">
        <?php if(session('ok')): ?> <div class="alert alert-success"><?php echo e(session('ok')); ?></div> <?php endif; ?>
        <form method="POST" action="<?php echo e(route('reset.handle')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" name="MatKhau" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="MatKhau_confirmation" class="form-control" required>
          </div>
          <button class="btn btn-success w-100">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/auth/reset.blade.php ENDPATH**/ ?>