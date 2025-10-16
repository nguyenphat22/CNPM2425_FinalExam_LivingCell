@extends('layouts.app')
@section('title','CTCT-HSSV')

@section('content')
<div class="row">
  <aside class="col-md-3 mb-3">
    <div class="list-group">
      <a class="list-group-item list-group-item-action active">Danh sách sinh viên</a>
      <a class="list-group-item list-group-item-action">Quản lý điểm rèn luyện</a>
    </div>
  </aside>
  <main class="col-md-9">
    {{-- Danh sách sinh viên --}}
    <h5>Danh sách sinh viên</h5>
    <div class="d-flex gap-2 mb-2">
      <button class="btn btn-secondary">Nhập file Excel ds sinh viên</button>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSV">Thêm</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editSV">Sửa</button>
      <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delSV">Xóa</button>
      <button class="btn btn-warning">Lưu (Cập nhật)</button>
      <form class="ms-auto d-flex">
        <input class="form-control me-2" placeholder="Tìm..."><button class="btn btn-outline-primary">Tìm</button>
      </form>
    </div>
    <div class="table-responsive mb-4">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr><th>STT</th><th>MSSV</th><th>Họ và Tên</th><th>Ngày sinh</th><th>Khoa</th><th>Lớp</th></tr>
        </thead>
        <tbody><tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>2004-01-01</td><td>SP Toán</td><td>10A1</td></tr></tbody>
      </table>
    </div>

    {{-- Quản lý điểm rèn luyện --}}
    <h5>Quản lý điểm rèn luyện</h5>
    <div class="d-flex gap-2 mb-2">
      <button class="btn btn-secondary">Nhập file Excel</button>
      <button class="btn btn-success">Xuất báo cáo file Excel</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editDRL">Sửa</button>
      <button class="btn btn-danger">Xóa</button>
      <button class="btn btn-warning">Lưu (Cập nhật)</button>
      <div class="ms-auto d-flex gap-2">
        <select class="form-select"><option>HK1 - 2024-2025</option></select>
        <input class="form-control" placeholder="Tìm...">
        <button class="btn btn-outline-primary">Tìm</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr><th>STT</th><th>MSSV</th><th>Họ và Tên</th><th>Điểm rèn luyện</th><th>Xếp loại</th></tr>
        </thead>
        <tbody><tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>85</td><td>Tốt</td></tr></tbody>
      </table>
    </div>
  </main>
</div>

{{-- Modals Thêm/Sửa SV và Sửa DRL (UI) --}}
<div class="modal fade" id="addSV" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm sinh viên</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control"></div>
        <div class="mb-2"><label class="form-label">Họ và tên</label><input class="form-control"></div>
        <div class="mb-2"><label class="form-label">Ngày sinh</label><input type="date" class="form-control"></div>
        <div class="mb-2"><label class="form-label">Khoa</label><input class="form-control"></div>
        <button class="btn btn-primary">Thêm</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editSV" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title">Sửa thông tin sinh viên</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">
    <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Họ và tên</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Ngày sinh</label><input type="date" class="form-control"></div>
    <div class="mb-2"><label class="form-label">Khoa</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Lớp</label><input class="form-control"></div>
    <button class="btn btn-primary">Lưu</button>
  </div>
</div></div></div>

<div class="modal fade" id="editDRL" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title">Sửa điểm rèn luyện</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">
    <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Họ và Tên</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Điểm rèn luyện</label><input class="form-control"></div>
    <div class="mb-2"><label class="form-label">Xếp loại</label><input class="form-control"></div>
    <button class="btn btn-primary">Lưu</button>
  </div>
</div></div></div>
@endsection
