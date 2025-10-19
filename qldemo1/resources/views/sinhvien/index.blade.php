{{-- resources/views/sinhvien/index.blade.php --}}
@extends('layouts.app')
@section('title','Sinh viên')

@section('content')
<div class="row">
  <main class="col-md-12">
    <h5 class="mb-3">Thông tin cá nhân</h5>
    <div class="card mb-3">
      <div class="card-body">
        @if($sv)
          <div><b>MSSV:</b> {{ $sv->MaSV }}</div>
          <div><b>Họ và Tên:</b> {{ $sv->HoTen }}</div>
          <div><b>Ngày sinh:</b> {{ $ngaySinh ?? '—' }}</div>
          <div><b>Lớp:</b> {{ $sv->Lop ?? '—' }}</div>
          <div><b>Khoa:</b> {{ $sv->Khoa ?? '—' }}</div>
        @else
          <em>Chưa liên kết thông tin sinh viên với tài khoản này.</em>
        @endif
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
          <div class="fs-5">{{ $gpaVal ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm rèn luyện</div>
          <div class="fs-5">{{ $drlVal ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Số ngày tình nguyện</div>
          <div class="fs-5">{{ $ntnTong }}</div>
        </div></div>
      </div>
    </div>

    {{-- Nếu KHÔNG muốn hiện chi tiết NTN, dừng tại đây --}}
    {{-- Nếu muốn bảng chi tiết, bọc đầy đủ <table> --}}
    {{--
    <h5 class="mt-4 mb-2">Ngày tình nguyện đã duyệt</h5>
    <div class="table-responsive">
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
          @forelse($ntnItems as $i => $row)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $row->TenHD ?? '' }}</td>
              <td>{{ $row->Ngay ?? '' }}</td>
              <td>{{ $row->SoNgay ?? '' }}</td>
              <td>{{ $row->TrangThaiDuyet ?? '' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    --}}
<br> 
    {{-- Khen thưởng danh hiệu --}}
<h5 class="mb-2" style= "text-align: center; color: green;">Khen thưởng danh hiệu</h5>
<div class="table-responsive mb-3">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px">STT</th>
        <th style="text-align: center">Danh hiệu đạt được</th>
        <th style="width:160px">Học kỳ</th> 
      </tr>
    </thead>
    <tbody>
      @forelse($awds as $i => $a)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>
            <span class="badge rounded-pill text-bg-success">{{ $a->Ten }}</span>
          </td>
          <td>{{ $a->HocKy ?? '—' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="text-center text-muted">Chưa có khen thưởng.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

    {{-- Gợi ý danh hiệu --}}
<h5 class="mt-4 mb-2" style = "text-align: center; color: green;">Đề xuất danh hiệu</h5>
<div class="card">
  <div class="card-body">
    @if(!empty($goiY))
      <ul class="mb-0">
        @foreach($goiY as $msg)
          <li>{{ $msg }}</li>
        @endforeach
      </ul>
    @else
      Hiện tại không có danh hiệu phù hợp, bạn hãy cố gắng hơn nhé!
    @endif
  </div>
</div>
@endsection
