@extends('layouts.app')
@section('title','VP Đoàn Trường | Quản lý danh hiệu')

@section('content')
<div class="row">

  <main class="col-md-9">
    <h5 class="mb-3">Quản lý danh hiệu</h5>

    <div class="d-flex gap-2 mb-3">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAward">Thêm (Danh hiệu)</button>
      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editAward">Sửa (Danh hiệu)</button>
      <button class="btn btn-danger">Xóa</button>
      <button class="btn btn-warning">Lưu (Cập nhật)</button>

      <div class="ms-auto d-flex gap-2">
        <select class="form-select">
          <option>HK1 - 2024-2025</option>
          <option>HK2 - 2024-2025</option>
        </select>
        <input class="form-control" placeholder="Tìm tên danh hiệu / tiêu chí">
        <button class="btn btn-outline-primary">Tìm</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:80px">STT</th>
            <th>Tên tiêu chí / danh hiệu</th>
            <th>Điều kiện điểm học tập</th>
            <th>Điều kiện điểm rèn luyện</th>
            <th>Điều kiện ngày tình nguyện</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td><td>Sinh viên 5 tốt</td><td>GPA ≥ 3.2</td><td>DRL ≥ 80</td><td>≥ 15 ngày</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

{{-- Modals danh hiệu --}}
<div class="modal fade" id="addAward" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm danh hiệu</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">Tên tiêu chí / Danh hiệu</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm học tập</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm rèn luyện</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện ngày tình nguyện</label><input class="form-control" required></div>
        <button class="btn btn-primary">Thêm</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editAward" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Sửa danh hiệu</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">Tên tiêu chí / Danh hiệu</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm học tập</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm rèn luyện</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện ngày tình nguyện</label><input class="form-control" required></div>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>
@endsection
