@extends('layouts.app')
@section('title','Danh sách sinh viên')

@section('content')
<h4 class="mb-3">Danh sách sinh viên</h4>

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
  <button id="btn-refresh" class="btn btn-success">
    <i class="bi bi-check-circle me-1"></i> Lưu
  </button>

  {{-- *** FORM IMPORT PHẢI CÓ form + POST + enctype + csrf *** --}}
  <form method="post"
        action="{{ route('ctct.sv.import') }}"
        enctype="multipart/form-data"
        class="d-flex gap-2">
    @csrf
    <input type="file" name="file" class="form-control"
           style="max-width:260px;" accept=".xlsx,.xls,.csv" required>
    <button class="btn btn-secondary" type="submit">
      Nhập file Excel ds sinh viên
    </button>
  </form>

  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
    Thêm
  </button>

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
            $d = $r->NgaySinh ? \Illuminate\Support\Carbon::parse($r->NgaySinh)->format('Y-m-d') : '';
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
            class="btn btn-sm btn-outline-primary me-1"
            data-bs-toggle="modal" data-bs-target="#modalEdit"
            data-masv="{{ $r->MaSV }}"
            data-hoten="{{ $r->HoTen }}"
            data-ngaysinh="{{ $r->NgaySinh }}"
            data-khoa="{{ $r->Khoa }}"
            data-lop="{{ $r->Lop }}">Sửa</button>

          <button type="button"
            class="btn btn-sm btn-outline-danger"
            data-bs-toggle="modal" data-bs-target="#modalDelete"
            data-masv="{{ $r->MaSV }}">Xóa</button>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>
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
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-danger">Xóa</button>
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
    document.getElementById('edit_masv').value     = btn.getAttribute('data-masv');
    document.getElementById('edit_hoten').value    = btn.getAttribute('data-hoten');
    document.getElementById('edit_ngaysinh').value = btn.getAttribute('data-ngaysinh');
    document.getElementById('edit_khoa').value     = btn.getAttribute('data-khoa') ?? '';
    document.getElementById('edit_lop').value      = btn.getAttribute('data-lop') ?? '';
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
@endpush
@endsection