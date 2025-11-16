@extends('layouts.app')
@section('title','Danh sách tài khoản')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<h4 class="page-title">Danh sách tài khoản</h4>

<div class="admin-toolbar card mb-3">
  <div class="card-body py-2 d-flex flex-wrap gap-2 align-items-center">
    <button class="btn btn-soft-secondary btn-animate ripple" data-bs-toggle="modal" data-bs-target="#modalAdd">
      <i class="bi bi-plus-circle"></i> Thêm
    </button>
{{-- Nút tải mẫu Excel --}}
<a href="{{ route('admin.accounts.template') }}"
   class="btn btn-soft-success btn-animate ripple">
  <i class="bi bi-file-earmark-excel"></i> Mẫu Excel
</a>
    <form method="post" action="{{ route('admin.accounts.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
      @csrf
      <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required style="max-width:280px;">
      <button class="btn btn-soft-primary btn-animate ripple">
        <i class="bi bi-cloud-upload"></i> Upload file
      </button>
    </form>

    <button class="btn btn-soft-warning btn-animate ripple" type="button" onclick="showSaveMessage()">
      <i class="bi bi-check-circle"></i> Lưu
    </button>

    <form class="ms-auto d-flex" method="get">
      <input class="form-control me-2" name="q" value="{{ $q }}" placeholder="Tìm...">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Tìm</button>
    </form>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">STT</th>
        <th>MaTK</th>
        <th>Tên đăng nhập</th>
        <th>Email</th>
        <th>Mật khẩu</th>
        <th>Vai trò</th>
        <th>Trạng thái</th>
        <th style="width:110px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaTK }}</td>
        <td>{{ $r->TenDangNhap }}</td>
        <td>{{ $r->Email }}</td>
        <td><code>••••••</code></td>
        <td>
  @php $role = strtolower($r->VaiTro); @endphp
  <span class="badge rounded-pill text-white badge-role {{ $role }}">
    {{ $r->VaiTro }}
  </span>
</td>

<td>
  @php $st = strtolower($r->TrangThai); @endphp
  <span class="badge rounded-pill text-white badge-state {{ $st }}">
    {{ $r->TrangThai }}
  </span>
</td>
        <td>
          <button type="button"
            class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
            data-bs-toggle="modal" data-bs-target="#modalEdit"
            data-matk="{{ $r->MaTK }}"
            data-tendn="{{ $r->TenDangNhap }}"
            data-vaitro="{{ $r->VaiTro }}"
            data-trangthai="{{ $r->TrangThai }}"
            data-email="{{ $r->Email }}">
            Sửa
          </button>

          <button type="button"
            class="btn btn-sm btn-outline-danger btn-animate ripple"
            data-bs-toggle="modal" data-bs-target="#confirmDelete"
            data-matk="{{ $r->MaTK }}"
            data-tendn="{{ $r->TenDangNhap }}">
            Xóa
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" class="text-center">Không có dữ liệu</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $data->links() }}

{{-- Modal THÊM (MaTK tự động – KHÔNG cần nhập) --}}
<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.accounts.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm Tài khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">

        <div class="mb-2">
          <label class="form-label">Tên đăng nhập</label>
          <input
            class="form-control @error('TenDangNhap','add') is-invalid @enderror"
            name="TenDangNhap"
            value="{{ old('TenDangNhap') }}"
            required>
          @error('TenDangNhap','add')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-2">
          <label class="form-label">Mật khẩu</label>
          <input
            type="password"
            class="form-control @error('MatKhau','add') is-invalid @enderror"
            name="MatKhau"
            required>
          @error('MatKhau','add')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-2">
          <label class="form-label">Vai trò</label>
          <select
            class="form-select @error('VaiTro','add') is-invalid @enderror"
            name="VaiTro"
            required>
            <option value="Admin" @selected(old('VaiTro')==='Admin' )>Admin</option>
            <option value="SinhVien" @selected(old('VaiTro')==='SinhVien' )>SinhVien</option>
            <option value="CTCTHSSV" @selected(old('VaiTro')==='CTCTHSSV' )>CTCTHSSV</option>
            <option value="KhaoThi" @selected(old('VaiTro')==='KhaoThi' )>KhaoThi</option>
            <option value="DoanTruong" @selected(old('VaiTro')==='DoanTruong' )>DoanTruong</option>
          </select>
          @error('VaiTro','add')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-2">
          <label class="form-label">Email</label>
          <input
            type="email"
            class="form-control @error('Email','add') is-invalid @enderror"
            name="Email"
            value="{{ old('Email') }}"
            placeholder="vd: user@example.com">
          @error('Email','add')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary btn-animate ripple">Lưu</button>
      </div>
    </form>
  </div>
</div>


{{-- Modal SỬA --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.accounts.update') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Sửa thông tin tài khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MaTK</label>
          <input class="form-control @error('MaTK','edit') is-invalid @enderror" name="MaTK" value="{{ old('MaTK') }}" readonly>
          @error('MaTK','edit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Tên đăng nhập</label>
          <input class="form-control @error('TenDangNhap','edit') is-invalid @enderror" name="TenDangNhap" value="{{ old('TenDangNhap') }}" required>
          @error('TenDangNhap','edit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Mật khẩu (để trống nếu giữ nguyên)</label>
          <input type="password" class="form-control @error('MatKhau','edit') is-invalid @enderror" name="MatKhau">
          @error('MatKhau','edit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Vai trò</label>
          <select class="form-select @error('VaiTro','edit') is-invalid @enderror" name="VaiTro" required>
            <option value="Admin" @selected(old('VaiTro')==='Admin' )>Admin</option>
            <option value="SinhVien" @selected(old('VaiTro')==='SinhVien' )>SinhVien</option>
            <option value="CTCTHSSV" @selected(old('VaiTro')==='CTCTHSSV' )>CTCTHSSV</option>
            <option value="KhaoThi" @selected(old('VaiTro')==='KhaoThi' )>KhaoThi</option>
            <option value="DoanTruong" @selected(old('VaiTro')==='DoanTruong' )>DoanTruong</option>
          </select>
          @error('VaiTro','edit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThai">
            <option @selected(old('TrangThai')==='Active' )>Active</option>
            <option @selected(old('TrangThai')==='Inactive' )>Inactive</option>
            <option @selected(old('TrangThai')==='Locked' )>Locked</option>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input type="email" class="form-control @error('Email','edit') is-invalid @enderror" name="Email" value="{{ old('Email') }}">
          @error('Email','edit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Lưu</button></div>
    </form>
  </div>
</div>

{{-- Modal XÁC NHẬN XÓA (DUY NHẤT) --}}
<div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="{{ route('admin.accounts.delete') }}" class="modal-content" id="deleteForm">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xóa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <p>Bạn chắc chắn muốn xóa tài khoản
          <strong id="delTen"></strong> (MaTK: <code id="delMa"></code>)?
        </p>
        <input type="hidden" name="MaTK" id="delMaInput">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-animate ripple" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-danger btn-animate ripple">Xóa</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  // Toast "Đã lưu thành công"
  function showSaveMessage() {
    const box = document.createElement('div');
    box.className = 'alert alert-success text-center';
    Object.assign(box.style, {
      position: 'fixed',
      top: '10px',
      left: '50%',
      transform: 'translateX(-50%)',
      zIndex: '2000',
      width: '350px'
    });
    box.textContent = '✅ Đã lưu thành công, đang chuyển về trang chính...';
    document.body.appendChild(box);
    setTimeout(() => {
      box.remove();
      // chuyển về trang danh sách sau khi hiển thị thông báo
      window.location.href = "{{ url('/admin/accounts') }}";
      // hoặc dùng replace để không lưu lịch sử: window.location.replace('http://127.0.0.1:8000/admin/accounts');
    }, 1500);
  }

  // Khởi tạo các handler khi DOM sẵn sàng
  document.addEventListener('DOMContentLoaded', () => {
    // ===== Modal XÁC NHẬN XÓA =====
    const delModal = document.getElementById('confirmDelete');
    if (delModal) {
      delModal.addEventListener('show.bs.modal', function(ev) {
        const btn = ev.relatedTarget; // nút Xóa vừa bấm
        const matk = btn?.getAttribute('data-matk') || '';
        const tendn = btn?.getAttribute('data-tendn') || '';
        this.querySelector('#delMa').textContent = matk;
        this.querySelector('#delTen').textContent = tendn;
        this.querySelector('#delMaInput').value = matk;
      });
    }

    // ===== Modal SỬA =====
    const editModal = document.getElementById('modalEdit');
    if (editModal) {
      editModal.addEventListener('show.bs.modal', function(ev) {
        // Trường hợp mở lại modal do lỗi validate (không có relatedTarget) => giữ nguyên old()
        const btn = ev.relatedTarget;
        if (!btn) return;

        const f = this.querySelector('form');
        f.querySelector('input[name="MaTK"]').value = btn.getAttribute('data-matk') || '';
        f.querySelector('input[name="TenDangNhap"]').value = btn.getAttribute('data-tendn') || '';
        f.querySelector('select[name="VaiTro"]').value = btn.getAttribute('data-vaitro') || 'SinhVien';
        f.querySelector('select[name="TrangThai"]').value = btn.getAttribute('data-trangthai') || 'Active';
        f.querySelector('input[name="Email"]').value = btn.getAttribute('data-email') || '';
        // Không tự fill mật khẩu — để trống nghĩa là giữ nguyên
        const pass = f.querySelector('input[name="MatKhau"]');
        if (pass) pass.value = '';
      });
    }
  });
</script>

@if ($errors->getBag('add')->any())
<script>
  document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalAdd')).show();
  });
</script>
@endif

@if ($errors->getBag('edit')->any())
<script>
  document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
  });
</script>
@endif
<script>
  // ===== Ripple effect cho mọi .ripple =====
  document.addEventListener('click', function(e){
    const t = e.target.closest('.ripple');
    if(!t) return;
    const rect = t.getBoundingClientRect();
    const d = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - d/2;
    const y = e.clientY - rect.top - d/2;

    const ink = document.createElement('span');
    ink.style.position='absolute';
    ink.style.borderRadius='50%';
    ink.style.pointerEvents='none';
    ink.style.width=ink.style.height=d+'px';
    ink.style.left=x+'px'; ink.style.top=y+'px';
    ink.style.background='rgba(255,255,255,.35)';
    ink.style.transform='scale(0)';
    ink.style.transition='transform .35s ease, opacity .55s ease';
    t.appendChild(ink);

    requestAnimationFrame(()=>{ ink.style.transform='scale(2.6)'; ink.style.opacity='0'; });
    setTimeout(()=>ink.remove(), 520);
  });

  // ===== Kích hoạt tooltip Bootstrap (nếu có) =====
  document.addEventListener('DOMContentLoaded', () => {
    [...document.querySelectorAll('[data-bs-toggle="tooltip"]')]
      .forEach(el => new bootstrap.Tooltip(el));
  });
</script>
@endpush
@endsection