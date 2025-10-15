<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $__env->yieldContent('title','Trang'); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">QLSV</a>
    <form class="ms-auto" method="post" action="<?php echo e(route('logout')); ?>">
      <?php echo csrf_field(); ?>
      <button class="btn btn-outline-light btn-sm">Đăng xuất</button>
    </form>
  </div>
</nav>

<div class="container py-4">
  <?php if(session('ok')): ?> <div class="alert alert-success"><?php echo e(session('ok')); ?></div> <?php endif; ?>
  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
      <ul class="mb-0 ps-3">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($e); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php echo $__env->yieldContent('content'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/layouts/app.blade.php ENDPATH**/ ?>