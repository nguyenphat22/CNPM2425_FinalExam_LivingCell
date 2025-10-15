@extends('layouts.app')
@section('title','Danh sách tài khoản')

@section('content')
<h4 class="mb-3">Danh sách tài khoản</h4>

<div class="d-flex gap-2 mb-3">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">Thêm</button>

  {{-- Form upload Excel --}}
  <form method="post" action="{{ route('admin.accounts.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
    @csrf
    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required style="max-width:280px;">
    <button class="btn btn-secondary">Upload file</button>
  </form>

  <button class="btn btn-warning" type="button" onclick="showSaveMessage()">Lưu(Update)</button>
  <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalEdit">Sửa</button>
  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete">Xóa</button>

  <form class="ms-auto d-flex" method="get">
    <input class="form-control me-2" name="q" value="{{ $q }}" placeholder="Tìm...">
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
    @forelse($data as $i => $r)
    <tr>
      <td>{{ $data->firstItem() + $i }}</td>
      <td>{{ $r->MaTK }}</td>
      <td>{{ $r->TenDangNhap }}</td>
      <td><code>••••••</code></td>
      <td>{{ $r->VaiTro }}</td>
      <td>{{ $r->TrangThai }}</td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $data->links() }}

{{-- Modal Thêm --}}
<div class="modal fade" id="modalAdd" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.accounts.store') }}">
      @csrf
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


{{-- Modal Sửa (nhập tay MaTK để demo) --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.accounts.update') }}">
      @csrf
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


{{-- Modal Xóa --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.accounts.delete') }}">
      @csrf
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
@push('scripts')
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
@endpush


@endsection
