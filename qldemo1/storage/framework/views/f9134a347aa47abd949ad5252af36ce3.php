<?php $__env->startSection('title','Quên mật khẩu'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">QUÊN MẬT KHẨU</div>
      <div class="card-body">
        <form method="POST" action="<?php echo e(route('forgot.handle')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input name="TenDangNhap" class="form-control" required value="<?php echo e(old('TenDangNhap')); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Email công tác</label>
            <input name="Email" type="email" class="form-control" required value="<?php echo e(old('Email')); ?>">
          </div>
          <button class="btn btn-primary w-100">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/auth/forgot.blade.php ENDPATH**/ ?>