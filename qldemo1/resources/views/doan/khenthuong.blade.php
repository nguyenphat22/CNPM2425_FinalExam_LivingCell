@extends('layouts.app')
@section('title','Danh sách khen thưởng sinh viên')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doan-khenthuong.css') }}">
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-3">Danh sách khen thưởng sinh viên</h5>

  <button class="btn btn-outline-primary btn-animate ripple"
          data-bs-toggle="modal" data-bs-target="#modalDoiMatKhau">
    <i class="bi bi-gear"></i> Đổi mật khẩu
  </button>
</div>

<div class="d-flex mb-3 gap-2">
  {{-- Học kỳ --}}
  <form id="hk-form" class="d-flex gap-2" method="get" action="{{ route('doan.khenthuong.index') }}">
    <select class="form-select" style="width:180px" name="hk" onchange="document.getElementById('hk-form').submit()">
      <option value="HK1-2024-2025" {{ $hk==='HK1-2024-2025' ? 'selected':'' }}>HK1-2024-2025</option>
      <option value="HK2-2024-2025" {{ $hk==='HK2-2024-2025' ? 'selected':'' }}>HK2-2024-2025</option>
    </select>
    @if(!empty($q))
      <input type="hidden" name="q" value="{{ $q }}">
    @endif
  </form>

  <a href="{{ route('doan.khenthuong.export', ['hk' => $hk]) }}" class="btn btn-soft-success btn-animate ripple">
  <i class="bi bi-file-earmark-excel me-1"></i> Xuất file Excel
</a>

  {{-- Form tìm kiếm --}}
  <form class="ms-auto d-flex" method="get" action="{{ route('doan.khenthuong.index') }}">
    <input type="hidden" name="hk" value="{{ $hk }}">
    <input class="form-control me-2" name="q" value="{{ $q ?? '' }}" placeholder="Tìm MSSV / Họ tên / Danh hiệu">
    <button class="btn btn-outline-primary btn-animate ripple" type="submit">Tìm</button>
  </form>
</div>

<!-- Modal Đổi Mật Khẩu Đoàn trường -->
<div class="modal fade" id="modalDoiMatKhau" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="{{ route('doan.password.change') }}" class="modal-content">
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
        <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
      </div>
    </form>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">

    <thead class="table-light">
      <tr>
        <th style="width:70px">STT</th>
        <th style="width:120px">MSSV</th>
        <th>Họ và Tên</th>
        <th>Danh hiệu đạt được</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ ($data->firstItem() ?? 0) + $i }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>
  @php
    $raw = $r->DanhHieu ?? '';
    $items = array_filter(array_map('trim', preg_split('/[,;]+/', $raw)));
    // map tên -> màu bootstrap
    $map = [
      'đoàn viên ưu tú' => 'success',
      'sinh viên 5 tốt' => 'primary',
      'hội nhập tốt'    => 'teal',   // dùng màu "info" gần nhất
      'gương mặt tiêu biểu' => 'warning',
    ];
  @endphp

  @if (count($items))
    @foreach ($items as $it)
      @php
        $key = mb_strtolower($it, 'UTF-8');
        $color = $map[$key] ?? 'secondary';
        // "teal" không có sẵn -> dùng info
        if ($color === 'teal') $color = 'info';
      @endphp
      <span class="badge text-bg-{{ $color }} badge-award">{{ $it }}</span>
    @endforeach
  @else
    <span class="text-muted">—</span>
  @endif
</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center text-muted">Không có dữ liệu.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Phân trang: đặt sau bảng --}}
<div class="mt-3">
  {{ $data->links('pagination::bootstrap-5') }}
</div>
@endsection
