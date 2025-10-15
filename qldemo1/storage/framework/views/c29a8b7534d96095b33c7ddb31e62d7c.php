<?php $__env->startSection('title','Đăng nhập'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">ĐĂNG NHẬP</div>
      <div class="card-body">
        <?php if(session('ok')): ?> <div class="alert alert-success"><?php echo e(session('ok')); ?></div> <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.submit')); ?>" novalidate>
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input name="TenDangNhap" class="form-control" required value="<?php echo e(old('TenDangNhap')); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="MatKhau" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
          <a href="<?php echo e(route('forgot.show')); ?>">Quên mật khẩu?</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/auth/login.blade.php ENDPATH**/ ?>