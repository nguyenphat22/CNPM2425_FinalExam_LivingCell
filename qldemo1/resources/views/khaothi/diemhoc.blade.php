@extends('layouts.app')
@section('title','Phòng Khảo thí | Quản lý điểm học tập')

@section('content')
<div class="row">

  <main class="col-md-9">
    <h5 class="mb-3">Quản lý điểm học tập</h5>

    <div class="d-flex gap-2 mb-3">
      <button class="btn btn-secondary">Nhập file Excel</button>
      <button class="btn btn-success">Xuất báo cáo file Excel</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editGPA">Sửa</button>
      <button class="btn btn-danger">Xóa</button>
      <button class="btn btn-warning">Lưu (Cập nhật)</button>

      <div class="ms-auto d-flex gap-2">
        <select class="form-select">
          <option>HK1 - 2024-2025</option>
          <option>HK2 - 2024-2025</option>
        </select>
        <input class="form-control" placeholder="Tìm MSSV / Họ tên">
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
            <th>Điểm học tập</th>
            <th>Xếp loại</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>3.60</td><td>Tốt</td></tr>
          <tr><td>2</td><td>SV002</td><td>Trần B</td><td>2.85</td><td>Khá</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

{{-- Modal Sửa điểm học tập --}}
<div class="modal fade" id="editGPA" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thay đổi điểm học tập</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <input class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Điểm học tập (GPA)</label>
          <input class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Xếp loại</label>
          <input class="form-control">
        </div>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>
@endsection
