@extends('layouts.app')
@section('title','VP Đoàn Trường | Quản lý ngày tình nguyện')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doan-tinhnguyen.css') }}">
<div class="row">
  <main class="col-md-12">
    <h5>Quản lý ngày tình nguyện</h5>

    <div class="d-flex gap-2 mb-2">
      <form method="post" action="{{ route('doan.tinhnguyen.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="file" class="form-control" style="max-width:260px" accept=".xlsx,.xls,.csv" required>
        <button class="btn btn-soft-secondary btn-animate ripple">
  <i class="bi bi-cloud-upload me-1"></i> Upload file
</button>
      </form>
{{-- Nút tải Mẫu Excel --}}
      <a href="{{ route('doan.tinhnguyen.template') }}"
         class="btn btn-soft-success btn-animate ripple">
        <i class="bi bi-file-earmark-excel me-1"></i> Mẫu Excel
      </a>
      <button class="btn btn-soft-primary btn-animate ripple" data-bs-toggle="modal" data-bs-target="#addNTN">
  <i class="bi bi-plus-circle me-1"></i> Thêm
</button>

      <button id="btn-refresh" class="btn btn-soft-warning btn-animate ripple" type="button" onclick="showSaveMessage()">
  <i class="bi-check-circle"></i> Lưu
</button>

      <div class="ms-auto d-flex gap-2">
        <form method="GET" action="{{ route('doan.tinhnguyen.index') }}" class="d-flex gap-2">
          <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên / Hoạt động">
          <button class="btn btn-outline-primary btn-animate ripple">Tìm</button>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>MSSV</th>
            <th>Họ và Tên</th>
            <th>Hoạt động đã tham gia</th>
            <th>Ngày tham gia</th>
            <th>Số ngày tình nguyện</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $r)
          <tr>
            <td>{{ $data->firstItem() + $i }}</td>
            <td>{{ $r->MaSV }}</td>
            <td>{{ $r->HoTen }}</td>
            <td>{{ $r->TenHoatDong ?? '— Chưa có —' }}</td>
            <td>{{ $r->NgayThamGiaText ?? '—' }}</td>
            <td>{{ $r->SoNgayTN ?? '—' }}</td>
           <td>
@php
  $st = strtolower($r->TrangThaiDuyet ?? '');
  $color = match ($st) {
    'daduyet'    => 'success',
    'chuaduyet'  => 'warning',
    'tuchoi'     => 'danger',
    default      => 'secondary'
  };
@endphp
<span class="badge text-bg-{{ $color }} badge-tn">
  {{ $r->TrangThaiDuyet ?? '—' }}
</span>
</td>
            <td class="text-nowrap">
              @if($r->MaNTN)
              <button type="button"
                class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
                data-bs-toggle="modal" data-bs-target="#editNTN"
                data-mantn="{{ $r->MaNTN }}"
                data-masv="{{ $r->MaSV }}"
                data-hoten="{{ $r->HoTen }}"
                data-tenhd="{{ $r->TenHoatDong }}"
                data-ngay="{{ $r->NgayThamGia }}"
                data-songay="{{ $r->SoNgayTN }}"
                data-trangthai="{{ $r->TrangThaiDuyet }}">
                Sửa
              </button>
              <form method="post" action="{{ route('doan.tinhnguyen.delete') }}" class="d-inline">
                @csrf
                <input type="hidden" name="MaNTN" value="{{ $r->MaNTN }}">
                <button class="btn btn-sm btn-outline-danger btn-animate ripple">Xóa</button>
              </form>
              @else
              <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">Không có dữ liệu</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      
  </main>
</div>
      {{ $data->links() }}
{{-- MODAL THÊM --}}
<div class="modal fade" id="addNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm hoạt động TN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <select class="form-select" name="MaSV" required>
            <option value="">-- Chọn MSSV --</option>
            @foreach($dsSV as $sv)
            <option value="{{ $sv->MaSV }}">{{ $sv->MaSV }} — {{ $sv->HoTen }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Tên hoạt động</label>
          <input class="form-control" name="TenHoatDong" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày tham gia</label>
          <input type="date" class="form-control" name="NgayThamGia" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Số ngày TN</label>
          <input type="number" min="1" class="form-control" name="SoNgayTN" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThaiDuyet" required>
            <option value="ChuaDuyet">Chưa duyệt</option>
            <option value="DaDuyet">Đã duyệt</option>
            <option value="TuChoi">Từ chối</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL SỬA (MSSV READONLY) --}}
<div class="modal fade" id="editNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.update') }}">
      @csrf
      <input type="hidden" name="MaNTN" id="edit_mantn">
      <div class="modal-header">
        <h5 class="modal-title">Sửa hoạt động TN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <input class="form-control" name="MaSV" id="edit_masv" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control" id="edit_hoten" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Tên hoạt động</label>
          <input class="form-control" name="TenHoatDong" id="edit_tenhd" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày tham gia</label>
          <input type="date" class="form-control" name="NgayThamGia" id="edit_ngay" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Số ngày TN</label>
          <input type="number" min="1" class="form-control" name="SoNgayTN" id="edit_songay" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThaiDuyet" id="edit_trangthai" required>
            <option value="ChuaDuyet">Chưa duyệt</option>
            <option value="DaDuyet">Đã duyệt</option>
            <option value="TuChoi">Từ chối</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Xoá --}}
<div class="modal fade" id="delNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.delete') }}">
      @csrf
      <input type="hidden" name="MaNTN" id="d_mantn">
      <div class="modal-header">
        <h5 class="modal-title">Xoá hoạt động</h5><button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Bạn chắc chắn muốn xoá hoạt động: <strong id="d_tenhd"></strong>?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button class="btn btn-danger">Xoá</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('btn-refresh')?.addEventListener('click', () => {
    const alertBox = document.createElement('div');
    alertBox.className = 'alert alert-success position-fixed top-0 end-0 m-3 shadow';
    alertBox.style.zIndex = '2000';
    alertBox.textContent = '✅ Đã cập nhật thành công! Đang quay lại...';
    document.body.appendChild(alertBox);
    setTimeout(() => {
      window.location.href = "{{ route('doan.tinhnguyen.index') }}";
    }, 1500);
  });
  document.getElementById('editNTN')?.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;
    const get = k => b.getAttribute('data-' + k) || '';
    document.getElementById('edit_mantn').value = get('mantn');
    document.getElementById('edit_masv').value = get('masv'); // readonly
    document.getElementById('edit_hoten').value = get('hoten'); // readonly
    document.getElementById('edit_tenhd').value = get('tenhd');
    document.getElementById('edit_ngay').value = get('ngay');
    document.getElementById('edit_songay').value = get('songay');
    document.getElementById('edit_trangthai').value = get('trangthai');
  });
</script>
<script>
  document.getElementById('btnRefresh')?.addEventListener('click', () => {
    const a = document.createElement('div');
    a.className = 'alert alert-success position-fixed top-0 end-0 m-3';
    a.textContent = 'Đã lưu thay đổi, đang tải lại...';
    document.body.appendChild(a);
    setTimeout(() => location.reload(), 1000);
  });

  // Đổ dữ liệu vào modal Sửa
  const editModal = document.getElementById('editNTN');
  editModal?.addEventListener('show.bs.modal', ev => {
    const b = ev.relatedTarget;
    document.getElementById('e_mantn').value = b.getAttribute('data-mantn');
    document.getElementById('e_masv').value = b.getAttribute('data-masv');
    document.getElementById('e_tenhd').value = b.getAttribute('data-tenhd');
    document.getElementById('e_ngay').value = b.getAttribute('data-ngay');
    document.getElementById('e_songay').value = b.getAttribute('data-songay');
    document.getElementById('e_trangthai').value = b.getAttribute('data-trangthai');
  });

  // Đổ dữ liệu vào modal Xoá
  const delModal = document.getElementById('delNTN');
  delModal?.addEventListener('show.bs.modal', ev => {
    const b = ev.relatedTarget;
    document.getElementById('d_mantn').value = b.getAttribute('data-mantn');
    document.getElementById('d_tenhd').textContent = b.getAttribute('data-tenhd');
  });
</script>
@endpush
@endsection