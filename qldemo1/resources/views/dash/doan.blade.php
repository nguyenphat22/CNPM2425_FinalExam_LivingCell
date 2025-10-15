@extends('layouts.app')
@section('title','Văn phòng Đoàn Trường')

@section('content')
<div class="row">
  <aside class="col-md-3 mb-3">
    <div class="list-group">
      <a class="list-group-item list-group-item-action active">Danh sách khen thưởng SV</a>
      <a class="list-group-item list-group-item-action">Quản lý ngày tình nguyện</a>
      <a class="list-group-item list-group-item-action">Quản lý danh hiệu</a>
    </div>
  </aside>

  <main class="col-md-9">
    {{-- ==== DANH SÁCH KHEN THƯỞNG ==== --}}
    <h5>Danh sách khen thưởng sinh viên</h5>
    <div class="d-flex gap-2 mb-2">
      <div class="d-flex gap-2">
        <select class="form-select">
          <option>HK1 - 2024-2025</option>
          <option>HK2 - 2024-2025</option>
        </select>
      </div>
      <form class="ms-auto d-flex">
        <input class="form-control me-2" placeholder="Tìm MSSV / Họ tên / Danh hiệu">
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
            <th>Danh hiệu đạt được</th>
            <th>Số quyết định</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>SV001</td><td>Nguyễn A</td><td>Sinh viên 5 tốt</td><td>QD-01/2025</td></tr>
        </tbody>
      </table>
      {{-- {{ $reward->links() }} --}}
    </div>

    {{-- ==== QUẢN LÝ NGÀY TÌNH NGUYỆN ==== --}}
    <h5>Quản lý ngày tình nguyện</h5>
    <div class="d-flex gap-2 mb-2">
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

    <div class="table-responsive mb-4">
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
      {{-- {{ $ntn->links() }} --}}
    </div>

    {{-- ==== QUẢN LÝ DANH HIỆU ==== --}}
    <h5>Quản lý danh hiệu</h5>
    <div class="d-flex gap-2 mb-2">
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
      {{-- {{ $awards->links() }} --}}
    </div>
  </main>
</div>

{{-- ==== MODALS: NGÀY TÌNH NGUYỆN ==== --}}
<div class="modal fade" id="addNTN" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm ngày tình nguyện</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MSSV</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Họ và tên</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Hoạt động tham gia (Tên hoạt động)</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Ngày tham gia</label><input type="date" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Số ngày tình nguyện</label><input class="form-control" placeholder="VD: 1, 2, 3" required></div>
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
        <div class="mb-2"><label class="form-label">Hoạt động tham gia (Tên hoạt động)</label><input class="form-control" required></div>
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

{{-- ==== MODALS: DANH HIỆU ==== --}}
<div class="modal fade" id="addAward" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm danh hiệu</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">Tên tiêu chí / Danh hiệu</label><input class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm học tập</label><input class="form-control" placeholder="VD: GPA ≥ 3.2" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện điểm rèn luyện</label><input class="form-control" placeholder="VD: DRL ≥ 80" required></div>
        <div class="mb-2"><label class="form-label">Điều kiện ngày tình nguyện</label><input class="form-control" placeholder="VD: ≥ 15 ngày" required></div>
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
