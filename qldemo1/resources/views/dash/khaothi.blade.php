@extends('layouts.app')
@section('title','Phòng Khảo thí')

@section('content')
<div class="row">
  <aside class="col-md-3 mb-3">
    <div class="list-group">
      <a class="list-group-item list-group-item-action active">Danh sách sinh viên</a>
      <a class="list-group-item list-group-item-action">Quản lý điểm học tập</a>
    </div>
  </aside>

  <main class="col-md-9">
    {{-- ==== DANH SÁCH SINH VIÊN ==== --}}
    <h5>Danh sách sinh viên</h5>
    <div class="d-flex gap-2 mb-2">
      <form class="ms-auto d-flex" role="search">
        <input class="form-control me-2" placeholder="Tìm MSSV / Họ tên / Lớp">
        <button class="btn btn-outline-primary">Tìm</button>
      </form>
    </div>

    <div class="table-responsive mb-4">
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
      {{-- {{ $sv->links() }} --}}
    </div>

    {{-- ==== QUẢN LÝ ĐIỂM HỌC TẬP ==== --}}
    <h5>Quản lý điểm học tập</h5>
    <div class="d-flex gap-2 mb-2">
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
      {{-- {{ $gpa->links() }} --}}
    </div>
  </main>
</div>

{{-- ==== MODAL: THAY ĐỔI ĐIỂM HỌC TẬP ==== --}}
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
          <input class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Điểm học tập (GPA)</label>
          <input class="form-control" placeholder="VD: 3.25" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Xếp loại</label>
          <input class="form-control" placeholder="Xuất sắc / Tốt / Khá / ...">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>
@endsection
