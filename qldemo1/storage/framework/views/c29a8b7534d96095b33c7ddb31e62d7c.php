<?php $__env->startSection('title','Đăng nhập'); ?>

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
      <?php if(session('ok')): ?>
        <div class="alert alert-success mb-3 py-2"><?php echo e(session('ok')); ?></div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('login.submit')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input type="text" name="TenDangNhap" value="<?php echo e(old('TenDangNhap')); ?>"
                 class="form-control <?php $__errorArgs = ['TenDangNhap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                 placeholder="Username" required>
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
          <label class="form-label">Mật khẩu</label>
          <input type="password" name="MatKhau"
                 class="form-control <?php $__errorArgs = ['MatKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                 placeholder="Password" required>
          <?php $__errorArgs = ['MatKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php if($errors->any() && !$errors->has('TenDangNhap') && !$errors->has('MatKhau')): ?>
            <div class="invalid-feedback d-block mt-1"><?php echo e($errors->first()); ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

        <div class="text-center mt-3 mb-1">
          <a href="<?php echo e(route('forgot.show')); ?>" class="forgot-link">Quên mật khẩu?</a>
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/auth/login.blade.php ENDPATH**/ ?>