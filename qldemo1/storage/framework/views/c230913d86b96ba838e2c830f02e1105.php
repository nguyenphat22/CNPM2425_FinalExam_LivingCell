<?php $__env->startSection('title','Danh sách tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<h4 class="mb-3">Danh sách tài khoản</h4>

<div class="d-flex gap-2 mb-3">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">Thêm</button>

  
  <form method="post" action="<?php echo e(route('admin.accounts.import')); ?>" enctype="multipart/form-data" class="d-flex gap-2">
    <?php echo csrf_field(); ?>
    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required style="max-width:280px;">
    <button class="btn btn-secondary">Upload file</button>
  </form>

  <button class="btn btn-warning" type="button" onclick="showSaveMessage()">Lưu(Update)</button>
  <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalEdit">Sửa</button>
  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete">Xóa</button>

  <form class="ms-auto d-flex" method="get">
    <input class="form-control me-2" name="q" value="<?php echo e($q); ?>" placeholder="Tìm...">
    <button class="btn btn-outline-primary">Tìm</button>
  </form>
</div>


<div class="table-responsive">
<table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th style="width:80px">STT</th>
      <th>MaTK(ID)</th>
      <th>Tên đăng nhập</th>
      <th>Mật khẩu</th>
      <th>Vai trò</th>
      <th>Trạng thái</th>
    </tr>
  </thead>
  <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
      <td><?php echo e($data->firstItem() + $i); ?></td>
      <td><?php echo e($r->MaTK); ?></td>
      <td><?php echo e($r->TenDangNhap); ?></td>
      <td><code>••••••</code></td>
      <td><?php echo e($r->VaiTro); ?></td>
      <td><?php echo e($r->TrangThai); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>
    <?php endif; ?>
  </tbody>
</table>
</div>

<?php echo e($data->links()); ?>



<div class="modal fade" id="modalAdd" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?php echo e(route('admin.accounts.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="modal-header"><h5 class="modal-title">Thêm Tài khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MaTK(ID)</label>
          <input class="form-control" name="MaTK" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Tên đăng nhập</label>
          <input class="form-control" name="TenDangNhap" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Mật khẩu</label>
          <input class="form-control" name="MatKhau" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Vai trò</label>
          <select class="form-select" name="VaiTro" required>
            <option value="Admin">Admin</option>
            <option value="SinhVien">SinhVien</option>
            <option value="CTCTHSSV">CTCTHSSV</option>
            <option value="KhaoThi">KhaoThi</option>
            <option value="DoanTruong">DoanTruong</option>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input class="form-control" name="Email">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu(Update)</button>
      </div>
    </form>
  </div>
</div>



<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?php echo e(route('admin.accounts.update')); ?>">
      <?php echo csrf_field(); ?>
      <div class="modal-header"><h5 class="modal-title">Sửa thông tin tài khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MaTK(ID)</label>
          <input class="form-control" name="MaTK" required></div>
        <div class="mb-2"><label class="form-label">Tên đăng nhập</label>
          <input class="form-control" name="TenDangNhap" required></div>
        <div class="mb-2"><label class="form-label">Mật khẩu (để trống nếu giữ nguyên)</label>
          <input class="form-control" name="MatKhau"></div>
        <div class="mb-2"><label class="form-label">Vai trò</label>
          <select class="form-select" name="VaiTro" required>
            <option value="Admin">Admin</option><option value="SinhVien">SinhVien</option>
            <option value="CTCTHSSV">CTCTHSSV</option><option value="KhaoThi">KhaoThi</option>
            <option value="DoanTruong">DoanTruong</option>
          </select></div>
        <div class="mb-2"><label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThai">
            <option>Active</option><option>Inactive</option><option>Locked</option>
          </select></div>
        <div class="mb-2"><label class="form-label">Email</label>
          <input class="form-control" name="Email"></div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Lưu</button></div>
    </form>
  </div>
</div>



<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?php echo e(route('admin.accounts.delete')); ?>">
      <?php echo csrf_field(); ?>
      <div class="modal-header"><h5 class="modal-title">Xóa tài khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <label class="form-label">Nhập MaTK cần xóa</label>
        <input class="form-control" name="MaTK" required>
      </div>
      <div class="modal-footer"><button class="btn btn-danger">Xóa</button></div>
    </form>
  </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
function showSaveMessage() {
  // Hiện thông báo
  const alertBox = document.createElement('div');
  alertBox.className = 'alert alert-success text-center';
  alertBox.style.position = 'fixed';
  alertBox.style.top = '10px';
  alertBox.style.left = '50%';
  alertBox.style.transform = 'translateX(-50%)';
  alertBox.style.zIndex = '1055';
  alertBox.style.width = '350px';
  alertBox.style.padding = '10px 15px';
  alertBox.textContent = '✅ Đã lưu thành công!';
  document.body.appendChild(alertBox);

  // Ẩn sau 1.5s rồi reload trang
  setTimeout(() => {
    alertBox.remove();
    window.location.reload();
  }, 1500);
}
</script>
<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\qldemo1\resources\views/admin/accounts/index.blade.php ENDPATH**/ ?>