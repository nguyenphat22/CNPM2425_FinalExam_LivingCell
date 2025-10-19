@extends('layouts.app')
@section('title', 'Phòng Khảo thí | Danh sách sinh viên')

@section('content')
<h5 class="mb-3">Danh sách sinh viên</h5>

<form method="get" class="d-flex mb-3">
  <input class="form-control me-2" name="q" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên / Lớp">
  <button class="btn btn-outline-primary">Tìm</button>
</form>

<div class="table-responsive">
  <table class="table table-bordered align-middle">
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

{{ $data->links() }}
@endsection