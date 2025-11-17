@extends('layouts.app')
@section('title','Danh sách sinh viên')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ctct.css') }}">
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-3">Danh sách sinh viên</h4>

  <button class="btn btn-soft-primary btn-animate ripple"
    data-bs-toggle="modal"
    data-bs-target="#modalChangePassword">
    <i class="bi bi-gear-fill me-1"></i> Đổi mật khẩu
  </button>
</div>

{{-- THÔNG BÁO --}}
@if ($errors->has('file'))
<div class="alert alert-danger">{{ $errors->first('file') }}</div>
@endif
@if (session('failures') && session('failures')->isNotEmpty())
<div class="alert alert-warning">
  <div class="fw-semibold mb-2">Một số dòng không thể nhập:</div>
  <ul class="mb-0 ps-3">
    @foreach(session('failures') as $msg)
    <li>{{ $msg }}</li>
    @endforeach
  </ul>
</div>
@endif

{{-- thanh công cụ: Lưu giả + Import + Thêm + Tìm --}}
<div class="d-flex gap-2 mb-3 align-items-center">
  {{-- Nút Lưu giả (refresh trang + thông báo) --}}
  <button id="btn-refresh" class="btn btn-soft-third btn-animate ripple">
    <i class="bi bi-check-circle me-1"></i> Lưu
  </button>
  {{-- Nút Mẫu Excel --}}
  <a href="{{ route('ctct.sv.template') }}"
    class="btn btn-soft-success btn-animate ripple">
    <i class="bi bi-file-earmark-excel me-1"></i> Mẫu Excel
  </a>
  {{-- *** FORM IMPORT PHẢI CÓ form + POST + enctype + csrf *** --}}
  <form method="post"
    action="{{ route('ctct.sv.import') }}"
    enctype="multipart/form-data"
    class="d-flex gap-2">
    @csrf
    <input type="file" name="file" class="form-control"
      style="max-width:260px;" accept=".xlsx,.xls,.csv" required>
    <button class="btn btn-soft-secondary btn-animate ripple">
      <i class="bi bi-cloud-upload me-1"></i> Upload file
    </button>
  </form>

  <button class="btn btn-soft-primary btn-animate ripple" data-bs-toggle="modal" data-bs-target="#modalAdd">
    <i class="bi bi-plus-circle me-1"></i> Thêm
  </button>

  <form class="ms-auto d-flex" method="get">
    <input class="form-control me-2" name="q" value="{{ $q }}" placeholder="Tìm...">
    <button class="btn btn-outline-primary btn-animate ripple">Tìm</button>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">STT</th>
        <th style="width:120px">MSSV</th>
        <th>Họ và Tên</th>
        <th style="width:140px">Ngày sinh</th>
        <th style="width:160px">Khoa</th>
        <th style="width:120px">Lớp</th>
        <th style="width:140px">MaTK</th>
        <th style="width:120px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>
          @php
          $d = $r->NgaySinh
          ? \Illuminate\Support\Carbon::parse($r->NgaySinh)->format('d/m/Y')
          : '';
          @endphp
          {{ $d }}
        </td>
        <td>{{ $r->Khoa }}</td>
        <td>{{ $r->Lop }}</td>
        {{-- ✅ Cột MaTK — phải nằm trong foreach này --}}
        <td>
          @if ($r->MaTK)
          <span class="badge text-bg-secondary">{{ $r->MaTK }}</span>
          @else
          <span class="text-muted">—</span>
          @endif
        </td>
        <td>
          <button type="button"
            class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
            data-bs-toggle="modal" data-bs-target="#modalEdit"
            data-masv="{{ $r->MaSV }}"
            data-hoten="{{ $r->HoTen }}"
            data-ngaysinh="{{ $r->NgaySinh }}"
            data-khoa="{{ $r->Khoa }}"
            data-lop="{{ $r->Lop }}"
            data-matk="{{ $r->MaTK }}">Sửa</button>

          <button type="button"
            class="btn btn-sm btn-outline-danger btn-animate ripple"
            data-bs-toggle="modal" data-bs-target="#modalDelete"
            data-masv="{{ $r->MaSV }}">Xóa</button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">Không có dữ liệu</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $data->links() }}

{{-- MODAL THÊM --}}
<div class="modal fade" id="modalAdd" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('ctct.sv.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm sinh viên</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <input class="form-control @error('MaSV') is-invalid @enderror" name="MaSV" value="{{ old('MaSV') }}" required>
          @error('MaSV')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control @error('HoTen') is-invalid @enderror" name="HoTen" value="{{ old('HoTen') }}" required>
          @error('HoTen')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày sinh</label>
          <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror" name="NgaySinh" value="{{ old('NgaySinh') }}" required>
          @error('NgaySinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Khoa</label>
          <input class="form-control @error('Khoa') is-invalid @enderror" name="Khoa" value="{{ old('Khoa') }}">
          @error('Khoa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
          <label class="form-label">Lớp</label>
          <input class="form-control @error('Lop') is-invalid @enderror" name="Lop" value="{{ old('Lop') }}">
          @error('Lop')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
          <label class="form-label">MaTK (tùy chọn)</label>
          <input class="form-control @error('MaTK') is-invalid @enderror"
            name="MaTK" value="{{ old('MaTK') }}"
            placeholder="Nhập mã tài khoản (nếu có)">
          @error('MaTK')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-animate ripple">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL SỬA --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('ctct.sv.update') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Sửa sinh viên</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          {{-- KHÔNG cho đổi MaSV để tránh lỗi khóa chính --}}
          <input class="form-control" name="MaSV" id="edit_masv" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control" name="HoTen" id="edit_hoten" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày sinh</label>
          <input type="date" class="form-control" name="NgaySinh" id="edit_ngaysinh" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Khoa</label>
          <input class="form-control" name="Khoa" id="edit_khoa">
        </div>
        <div class="mb-2">
          <label class="form-label">Lớp</label>
          <input class="form-control" name="Lop" id="edit_lop">
        </div>
        <div class="mb-2">
          <label class="form-label">MaTK (tùy chọn)</label>
          <input class="form-control" name="MaTK" id="edit_matk" placeholder="Nhập mã tài khoản (nếu có)">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL XÓA --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('ctct.sv.delete') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xóa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="MaSV" id="del_masv_input">
        Bạn chắc chắn muốn xóa sinh viên MaSV: <strong id="del_masv_text"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-animate ripple" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-danger btn-animate ripple">Xóa</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Đổi Mật Khẩu -->
<div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('ctct.password.change') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Đổi mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Mật khẩu cũ</label>
        <input type="password"
               name="old_password"
               class="form-control"
               placeholder="Nhập mật khẩu hiện tại"
               required>
        @error('old_password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

        <div class="mb-3">
          <label class="form-label">Mật khẩu mới</label>
          <input type="password"
            name="new_password"
            class="form-control"
            placeholder="Tối thiểu 6 ký tự"
            required>

          @error('new_password')
          <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Nhập lại mật khẩu mới</label>
          <input type="password"
            name="new_password_confirmation"
            class="form-control"
            placeholder="Nhập lại mật khẩu mới"
            required>

          @error('new_password_confirmation')
          <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary">Đổi mật khẩu</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  // Nút Lưu giả (refresh trang + thông báo)
  document.getElementById('btn-refresh')?.addEventListener('click', () => {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success position-fixed top-0 end-0 m-3 shadow';
    alert.style.zIndex = '2000';
    alert.textContent = '✅ Đã lưu dữ liệu, đang quay lại trang chính...';
    document.body.appendChild(alert);

    // Sau 1.5 giây quay lại trang /ctct/sinhvien
    setTimeout(() => {
      window.location.href = "{{ url('/ctct/sinhvien') }}";
    }, 1500);
  });
  document.addEventListener('DOMContentLoaded', () => {
    // --- SỬA ---
    const editModal = document.getElementById('modalEdit');
    editModal?.addEventListener('show.bs.modal', ev => {
      const btn = ev.relatedTarget;
      document.getElementById('edit_masv').value = btn.getAttribute('data-masv');
      document.getElementById('edit_hoten').value = btn.getAttribute('data-hoten');
      document.getElementById('edit_ngaysinh').value = btn.getAttribute('data-ngaysinh');
      document.getElementById('edit_khoa').value = btn.getAttribute('data-khoa') ?? '';
      document.getElementById('edit_lop').value = btn.getAttribute('data-lop') ?? '';
      document.getElementById('edit_matk').value = btn.getAttribute('data-matk') ?? '';
    });

    // --- XÓA ---
    const delModal = document.getElementById('modalDelete');
    delModal?.addEventListener('show.bs.modal', ev => {
      const btn = ev.relatedTarget;
      const masv = btn.getAttribute('data-masv');
      document.getElementById('del_masv_input').value = masv;
      document.getElementById('del_masv_text').textContent = masv;
    });
  });
</script>
<script>
  // Ripple effect cho .ripple
  document.addEventListener('click', function(e) {
    const t = e.target.closest('.ripple');
    if (!t) return;
    const r = t.getBoundingClientRect(),
      d = Math.max(r.width, r.height);
    const x = e.clientX - r.left - d / 2,
      y = e.clientY - r.top - d / 2;
    const ink = document.createElement('span');
    Object.assign(ink.style, {
      position: 'absolute',
      borderRadius: '50%',
      pointerEvents: 'none',
      width: d + 'px',
      height: d + 'px',
      left: x + 'px',
      top: y + 'px',
      background: 'rgba(255,255,255,.35)',
      transform: 'scale(0)',
      transition: 'transform .35s ease, opacity .55s ease'
    });
    t.appendChild(ink);
    requestAnimationFrame(() => {
      ink.style.transform = 'scale(2.6)';
      ink.style.opacity = '0';
    });
    setTimeout(() => ink.remove(), 520);
  });

  // Highlight hàng khi mở modal Sửa/Xóa
  document.addEventListener('DOMContentLoaded', () => {
    let lastGlow;
    const glow = (btn) => {
      if (lastGlow) lastGlow.classList.remove('glow');
      lastGlow = btn.closest('tr');
      lastGlow?.classList.add('glow');
    };

    const editModal = document.getElementById('modalEdit');
    editModal?.addEventListener('show.bs.modal', ev => {
      glow(ev.relatedTarget);
    });

    const delModal = document.getElementById('modalDelete');
    delModal?.addEventListener('show.bs.modal', ev => {
      glow(ev.relatedTarget);
    });

    // Bỏ highlight khi đóng modal
    ['hidden.bs.modal', 'hide.bs.modal'].forEach(evt => {
      editModal?.addEventListener(evt, () => lastGlow?.classList.remove('glow'));
      delModal?.addEventListener(evt, () => lastGlow?.classList.remove('glow'));
    });
  });
</script>
@endpush
@endsection