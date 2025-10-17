@extends('layouts.app')
@section('title','Phòng Khảo thí | Danh sách sinh viên')

@section('content')
<div class="row">
    
  <main class="col-md-9">
    <h5 class="mb-3">Danh sách sinh viên</h5>

    <div class="d-flex gap-2 mb-3">
      <form class="ms-auto d-flex" role="search">
        <input class="form-control me-2" placeholder="Tìm MSSV / Họ tên / Lớp">
        <button class="btn btn-outline-primary">Tìm</button>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:80px">STT</th>
            <th>MSSV</th>
            <th>Họ và Tên</th>
            <th>Ngày sinh</th>
            <th>Khoa</th>
            <th>Lớp</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>2004-01-01</td><td>SP Toán</td><td>10A1</td></tr>
          <tr><td>2</td><td>SV002</td><td>Trần B</td><td>2004-05-12</td><td>SP Lý</td><td>10A2</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection
