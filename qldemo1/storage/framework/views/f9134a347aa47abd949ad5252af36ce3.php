<?php $__env->startSection('title','Quên mật khẩu'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
  
  <div class="auth-left">
    
    <div class="brand-combo">
      <img src="<?php echo e(asset('assets/images/logo_truong.png')); ?>" alt="HCMUE Logo & Name">
    </div>

    
    <div class="club">
      <img src="<?php echo e(asset('assets/images/logo_doan.png')); ?>" alt="Logo Đoàn">
      <img src="<?php echo e(asset('assets/images/logo_hoi.png')); ?>" alt="Logo Hội SV">
    </div>

    
    <div class="title">
      HỆ THỐNG NGHIỆP VỤ<br>
      CÔNG TÁC RÈN LUYỆN SINH VIÊN
    </div>

    
    <div class="card-box">
      <div class="text-center fw-bold mb-2" style="font-size:14px;">QUÊN MẬT KHẨU</div>

      <form method="POST" action="<?php echo e(route('forgot.handle')); ?>" novalidate>
        <?php echo csrf_field(); ?>

        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input
            type="text"
            name="TenDangNhap"
            value="<?php echo e(old('TenDangNhap')); ?>"
            class="form-control <?php $__errorArgs = ['TenDangNhap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="Nhập tên đăng nhập"
            required>
          <?php $__errorArgs = ['TenDangNhap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Email công tác</label>
          <input
            type="email"
            name="Email"
            value="<?php echo e(old('Email')); ?>"
            class="form-control <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="name@yourdomain.edu.vn"
            required>
          <?php $__errorArgs = ['Email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <?php if($errors->has('credentials')): ?>
          <div class="invalid-feedback d-block mb-2">
            <?php echo e($errors->first('credentials')); ?>

          </div>
        <?php endif; ?>

        <button class="btn btn-primary w-100">Xác nhận</button>

        <div class="text-center mt-3">
          <a href="<?php echo e(route('login.show')); ?>" class="forgot-link">← Quay lại đăng nhập</a>
        </div>
      </form>
    </div>

    <div class="footer-note">
      All Rights <b>NOT</b> Reserved Developed by PSC
    </div>
  </div>

  
  <div class="auth-right"></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/auth/forgot.blade.php ENDPATH**/ ?>