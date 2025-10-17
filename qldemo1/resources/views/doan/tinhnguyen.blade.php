@extends('layouts.app')
@section('title','VP Đoàn Trường | Quản lý ngày tình nguyện')

@section('content')
<div class="row">

  <main class="col-md-9">
    <h5 class="mb-3">Quản lý ngày tình nguyện</h5>

    <div class="d-flex gap-2 mb-3">
      <button class="btn btn-secondary">Nhập file Excel</button>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNTN">Thêm</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editNTN">Sửa</button>
      <button class="btn btn-danger">Xóa</button>
      <button class="btn btn-warning">Lưu (Cập nhật)</button>

      <div class="ms-auto d-flex gap-2">
        <select class="form-select">
          <option>HK1 - 2024-2025</option>
          <option>HK2 - 2024-2025</option>
        </select>
        <input class="form-control" placeholder="Tìm MSSV / Hoạt động">
        <button class="btn btn-outline-primary">Tìm</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:80px">STT</th>
            <th>MSSV</th>
            <th>Họ và Tên</th>
            <th>Hoạt động đã tham gia (Tên hoạt động)</th>
            <th>Ngày tham gia</th>
            <th>Số ngày tình nguyện</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td><td>SV001</td><td>Nguyễn A</td>
            <td>Tiếp sức mùa thi</td><td>2025-06-10</td><td>2</td><td>Đã duyệt</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

{{-- Modals --}}
<div class="modal fade" id="addNTN" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm ngày tình nguyện</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Họ và tên</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Hoạt động tham gia</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Ngày tham gia</label><input type="date" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Số ngày tình nguyện</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Trạng thái</label>
          <select class="form-select"><option>Chờ duyệt</option><option>Đã duyệt</option></select>
        </div>
        <button class="btn btn-primary">Thêm</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editNTN" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Sửa ngày tình nguyện</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Họ và tên</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Hoạt động tham gia</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Ngày tham gia</label><input type="date" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Số ngày tình nguyện</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Trạng thái</label>
          <select class="form-select"><option>Chờ duyệt</option><option>Đã duyệt</option></select>
        </div>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>
@endsection
