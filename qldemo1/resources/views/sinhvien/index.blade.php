{{-- resources/views/sinhvien/index.blade.php --}}
@extends('layouts.app')
@section('title','Sinh viên')

@section('content')
<div class="row">
  {{-- Sidebar bên trái chỉ 1 mục --}}

  <main class="col-md-9">
    {{-- Thông tin cá nhân --}}
    <h5 class="mb-3">Thông tin cá nhân</h5>
    <div class="card mb-3">
      <div class="card-body">
        @if($sv)
          <div><b>MSSV:</b> {{ $sv->MaSV }}</div>
          <div><b>Họ và Tên:</b> {{ $sv->HoTen }}</div>
          <div><b>Ngày sinh:</b> {{ $sv->NgaySinh }}</div>
          <div><b>Lớp:</b> {{ $sv->Lop }}</div>
          <div><b>Khoa:</b> {{ $sv->Khoa }}</div>
        @else
          <em>Chưa liên kết thông tin sinh viên với tài khoản này.</em>
        @endif
      </div>
    </div>

    {{-- Tổng quan học tập / rèn luyện / NTN --}}
    <h5 class="mb-2">Tổng quan</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
          <div class="fs-5">{{ $gpa->DiemHe4 ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm rèn luyện</div>
          <div class="fs-5">{{ $drl->DiemRL ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Số ngày tình nguyện</div>
          <div class="fs-5">{{ $ntn->tong ?? 0 }}</div>
        </div></div>
      </div>
    </div>

    {{-- Danh sách ngày tình nguyện (nếu muốn show) --}}
    <h5 class="mt-4 mb-2">Ngày tình nguyện</h5>
    <div class="table-responsive mb-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:70px">STT</th>
            <th>Hoạt động</th>
            <th>Ngày tham gia</th>
            <th>Số ngày</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          @forelse(($ntn->items ?? []) as $i => $row)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $row->TenHD ?? '' }}</td>
              <td>{{ $row->Ngay ?? '' }}</td>
              <td>{{ $row->SoNgay ?? '' }}</td>
              <td>{{ $row->TrangThai ?? '' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Khen thưởng (nếu có) --}}
    <h5 class="mb-2">Khen thưởng</h5>
    <div class="table-responsive mb-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:70px">STT</th>
            <th>Danh hiệu</th>
            <th>Số quyết định</th>
            <th>Học kỳ</th>
          </tr>
        </thead>
        <tbody>
          @forelse($awds as $i => $a)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $a->Ten ?? '' }}</td>
              <td>{{ $a->SoQD ?? '' }}</td>
              <td>{{ $a->HocKy ?? '' }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted">Chưa có khen thưởng.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Gợi ý danh hiệu --}}
    <h5 class="mt-4 mb-2">Đề xuất danh hiệu</h5>
    <div class="card"><div class="card-body">
      {{ $goiY ? "Gợi ý: $goiY" : 'Chưa đủ dữ liệu để gợi ý.' }}
    </div></div>
  </main>
</div>
@endsection
