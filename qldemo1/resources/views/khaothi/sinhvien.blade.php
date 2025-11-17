@extends('layouts.app')
@section('title', 'Phòng Khảo thí | Danh sách sinh viên')

@section('content')
<link rel="stylesheet" href="{{ asset('css/khaothi.css') }}">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Danh sách sinh viên</h4>

    <button class="btn btn-soft-primary btn-animate ripple"
            data-bs-toggle="modal"
            data-bs-target="#modalChangePassword">
        <i class="bi bi-gear-fill me-1"></i> Đổi mật khẩu
    </button>
</div>

<div class="khaothi-toolbar card mb-3">
  <form method="get" action="">
    <input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên / Lớp">
    <button type="submit" class="btn btn-outline-primary btn-animate ripple">
      <i class="bi bi-search"></i> Tìm
    </button>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:60px">STT</th>
        <th>MSSV</th>
        <th>Họ và Tên</th>
        <th>Ngày sinh</th>
        <th>Khoa</th>
        <th>Lớp</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>{{ $r->NgaySinh }}</td>
        <td>{{ $r->Khoa }}</td>
        <td>{{ $r->Lop }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center">Không có dữ liệu</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
{{-- MODAL ĐỔI MẬT KHẨU KHAOTHI --}}
<div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST"
          action="{{ route('khaothi.password.change') }}"
          class="modal-content">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title fw-bold">Đổi mật khẩu</h5>
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
    <div class="text-danger small mt-1">{{ $message }}</div>
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
    <div class="text-danger small mt-1">{{ $message }}</div>
  @enderror
</div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
  </div>
</div>
{{ $data->links() }}
@push('scripts')
<script>
  document.addEventListener('click', function(e){
    const t = e.target.closest('.ripple'); if(!t) return;
    const r = t.getBoundingClientRect(), d = Math.max(r.width, r.height);
    const x = e.clientX - r.left - d/2, y = e.clientY - r.top - d/2;
    const ink = document.createElement('span');
    Object.assign(ink.style,{
      position:'absolute', borderRadius:'50%', pointerEvents:'none',
      width:d+'px', height:d+'px', left:x+'px', top:y+'px',
      background:'rgba(255,255,255,.35)', transform:'scale(0)',
      transition:'transform .35s ease, opacity .55s ease'
    });
    t.appendChild(ink);
    requestAnimationFrame(()=>{ ink.style.transform='scale(2.6)'; ink.style.opacity='0'; });
    setTimeout(()=>ink.remove(),520);
  });
</script>
@endpush
@endsection