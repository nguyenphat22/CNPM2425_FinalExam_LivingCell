<?php $__env->startSection('title','Sinh viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <aside class="col-md-3 mb-3">
    <div class="list-group">
      <a class="list-group-item list-group-item-action active">Thông tin Sinh viên</a>
    </div>
  </aside>
  <main class="col-md-9">
    <h5 class="mb-3">Thông tin cá nhân</h5>
    <div class="card mb-3">
      <div class="card-body">
        <?php if($sv): ?>
          <div><b>MSSV:</b> <?php echo e($sv->MaSV); ?></div>
          <div><b>Họ và Tên:</b> <?php echo e($sv->HoTen); ?></div>
          <div><b>Ngày sinh:</b> <?php echo e($sv->NgaySinh); ?></div>
          <div><b>Lớp:</b> <?php echo e($sv->Lop); ?></div>
          <div><b>Khoa:</b> <?php echo e($sv->Khoa); ?></div>
        <?php else: ?>
          <em>Chưa liên kết thông tin sinh viên với tài khoản này.</em>
        <?php endif; ?>
      </div>
    </div>

    <h5 class="mb-2">Thông tin học tập / rèn luyện / NTN</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
          <div><?php echo e($gpa->DiemHe4 ?? '—'); ?></div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm rèn luyện</div>
          <div><?php echo e($drl->DiemRL ?? '—'); ?></div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Số ngày tình nguyện</div>
          <div><?php echo e($ntn->tong ?? 0); ?></div>
        </div></div>
      </div>
    </div>

    <h5 class="mt-4 mb-2">Đề xuất danh hiệu</h5>
    <div class="card"><div class="card-body">
      <?php echo e($goiY ? "Gợi ý: $goiY" : 'Chưa đủ dữ liệu để gợi ý.'); ?>

    </div></div>
  </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/dash/sinhvien.blade.php ENDPATH**/ ?>