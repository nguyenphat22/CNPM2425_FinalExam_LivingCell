{{-- resources/views/ctct/drl.blade.php --}}
@extends('layouts.app')
@section('title','CTCT-HSSV | Quản lý điểm rèn luyện')

@section('content')
<div class="row">

  <main class="col-md-9">
    <h5>Quản lý điểm rèn luyện</h5>

    <div class="d-flex gap-2 mb-2">
      <button class="btn btn-secondary">Nhập file Excel</button>
      <button class="btn btn-success">Xuất báo cáo file Excel</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editDRL">Sửa</button>
      <button class="btn btn-danger">Xóa</button>
      <button class="btn btn-warning">Lưu</button>

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
        <tbody>
          <tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>85</td><td>Tốt</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

{{-- Modal DRL --}}
<div class="modal fade" id="editDRL" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Sửa điểm rèn luyện</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control"></div>
      <div class="mb-2"><label class="form-label">Họ và Tên</label><input class="form-control"></div>
      <div class="mb-2"><label class="form-label">Điểm rèn luyện</label><input class="form-control"></div>
      <div class="mb-2"><label class="form-label">Xếp loại</label><input class="form-control"></div>
      <button class="btn btn-primary">Lưu</button>
    </div>
  </div></div>
</div>
@endsection
