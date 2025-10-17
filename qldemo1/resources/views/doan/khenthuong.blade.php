@extends('layouts.app')
@section('title','VP Đoàn Trường | Danh sách khen thưởng')

@section('content')
<div class="row">

  <main class="col-md-9">
    <h5 class="mb-3">Danh sách khen thưởng sinh viên</h5>

    <div class="d-flex gap-2 mb-3">
      <select class="form-select" style="max-width:220px">
        <option>HK1 - 2024-2025</option>
        <option>HK2 - 2024-2025</option>
      </select>

      <form class="ms-auto d-flex">
        <input class="form-control me-2" placeholder="Tìm MSSV / Họ tên / Danh hiệu">
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
            <th>Danh hiệu đạt được</th>
            <th>Số quyết định</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>Sinh viên 5 tốt</td><td>QD-01/2025</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection
