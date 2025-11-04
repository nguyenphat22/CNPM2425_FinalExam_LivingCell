@extends('layouts.app')
@section('title','Đoàn Trường | Quản lý danh hiệu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doan-danhhieu.css') }}">
<h5 class="mb-3">Quản lý danh hiệu</h5>

<div class="d-flex gap-2 mb-3 align-items-center">
  <button class="btn btn-soft-primary btn-animate ripple" data-bs-toggle="modal" data-bs-target="#modalAdd">
  <i class="bi bi-plus-circle me-1"></i> Thêm
</button>

  <button id="btn-refresh" class="btn btn-soft-warning btn-animate ripple" type="button" onclick="showSaveMessage()">
  <i class="bi-check-circle"></i> Lưu
</button>

  <form class="ms-auto d-flex gap-2" method="get">
    <select class="form-select" name="hk" style="width:140px">
      <option value="1" {{ (int)$hk===1?'selected':'' }}>HK1</option>
      <option value="2" {{ (int)$hk===2?'selected':'' }}>HK2</option>
      <option value="3" {{ (int)$hk===3?'selected':'' }}>HK Hè</option>
    </select>
    <input class="form-control" name="nh" value="{{ $nh }}" style="width:150px" placeholder="2024-2025">
    <input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm tên danh hiệu / tiêu chí">
    <button class="btn btn-outline-primary btn-animate">Tìm</button>
  </form>
</div>


<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:60px">STT</th>
        <th>Tên tiêu chí / danh hiệu</th>
        <th style="width:160px">Điều kiện điểm học tập</th>
        <th style="width:160px">Điều kiện điểm rèn luyện</th>
        <th style="width:200px">Điều kiện ngày tình nguyện</th>
        <th style="width:140px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->TenDH }}</td>
        <td>
  @if(!is_null($r->DieuKienGPA))
    <span class="badge text-bg-primary badge-req">GPA ≥ {{ $r->DieuKienGPA }}</span>
  @endif
</td>
<td>
  @if(!is_null($r->DieuKienDRL))
    <span class="badge text-bg-success badge-req">DRL ≥ {{ $r->DieuKienDRL }}</span>
  @endif
</td>
<td>
  @if(!is_null($r->DieuKienNTN))
    <span class="badge text-bg-warning badge-req">≥ {{ $r->DieuKienNTN }} ngày</span>
  @endif
</td>
        <td>
          <button class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
            data-bs-toggle="modal" data-bs-target="#modalEdit"
            data-madh="{{ $r->MaDH }}"
            data-tendh="{{ $r->TenDH }}"
            data-gpa="{{ $r->DieuKienGPA }}"
            data-drl="{{ $r->DieuKienDRL }}"
            data-ntn="{{ $r->DieuKienNTN }}">Sửa</button>

          <button class="btn btn-sm btn-outline-danger btn-animate ripple"
            data-bs-toggle="modal" data-bs-target="#modalDelete"
            data-madh="{{ $r->MaDH }}"
            data-tendh="{{ $r->TenDH }}">Xóa</button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center">Không có dữ liệu</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $data->links() }}

{{-- MODAL THÊM --}}
<div class="modal fade" id="modalAdd" tabindex="-1">
  <div class="modal-dialog">
    {{-- TẮT HTML5 validation để không hiện tooltip mặc định --}}
    <form class="modal-content" method="post" action="{{ route('doan.danhhieu.store') }}" novalidate>
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm danh hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- Tên danh hiệu --}}
        <div class="mb-2">
          <label class="form-label">Tên danh hiệu</label>
          <input
            class="form-control @error('TenDH') is-invalid @enderror"
            name="TenDH"
            value="{{ old('TenDH') }}">
          @error('TenDH')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- GPA --}}
        <div class="mb-2">
          <label class="form-label">Điều kiện GPA (0-4)</label>
          <input
            type="number" step="0.01" min="0" max="4"
            class="form-control @error('DieuKienGPA') is-invalid @enderror"
            name="DieuKienGPA"
            value="{{ old('DieuKienGPA') }}">
          @error('DieuKienGPA')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- DRL --}}
        <div class="mb-2">
          <label class="form-label">Điều kiện DRL (0-100)</label>
          <input
            type="number" step="1" min="0" max="100"
            class="form-control @error('DieuKienDRL') is-invalid @enderror"
            name="DieuKienDRL"
            value="{{ old('DieuKienDRL') }}">
          @error('DieuKienDRL')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Ngày TN --}}
        <div class="mb-2">
          <label class="form-label">Điều kiện ngày TN (số ngày)</label>
          <input
            type="number" step="1" min="0"
            class="form-control @error('DieuKienNTN') is-invalid @enderror"
            name="DieuKienNTN"
            value="{{ old('DieuKienNTN') }}">
          @error('DieuKienNTN')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- Tự mở lại modal nếu có lỗi validate --}}
@if ($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var m = new bootstrap.Modal(document.getElementById('modalAdd'));
    m.show();
  });
</script>
@endif

{{-- MODAL SỬA --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.danhhieu.update') }}">
      @csrf
      <input type="hidden" name="MaDH" id="edit_madh">
      <div class="modal-header">
        <h5 class="modal-title">Sửa danh hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">Tên danh hiệu</label>
          <input class="form-control" name="TenDH" id="edit_tendh" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Điều kiện GPA</label>
          <input type="number" step="0.01" min="0" max="4" class="form-control" name="DieuKienGPA" id="edit_gpa">
        </div>
        <div class="mb-2">
          <label class="form-label">Điều kiện DRL</label>
          <input type="number" step="1" min="0" max="100" class="form-control" name="DieuKienDRL" id="edit_drl">
        </div>
        <div class="mb-2">
          <label class="form-label">Điều kiện ngày TN</label>
          <input type="number" step="1" min="0" class="form-control" name="DieuKienNTN" id="edit_ntn">
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Lưu</button></div>
    </form>
  </div>
</div>

{{-- MODAL XÓA --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.danhhieu.delete') }}">
      @csrf
      <input type="hidden" name="MaDH" id="del_madh">
      <div class="modal-header">
        <h5 class="modal-title">Xóa danh hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Bạn chắc chắn muốn xóa danh hiệu: <b id="del_tendh"></b>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-danger">Xóa</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Nút Lưu: giống các trang trước – thông báo rồi quay lại
    document.getElementById('btn-refresh')?.addEventListener('click', () => {
      const alertBox = document.createElement('div');
      alertBox.className = 'alert alert-success position-fixed top-0 end-0 m-3 shadow';
      alertBox.style.zIndex = '2000';
      alertBox.textContent = '✅ Đã cập nhật thành công! Đang quay lại...';
      document.body.appendChild(alertBox);
      setTimeout(() => {
        window.location.href = "{{ route('doan.danhhieu.index') }}";
      }, 1500);
    });

    // Modal Sửa
    const editModal = document.getElementById('modalEdit');
    editModal?.addEventListener('show.bs.modal', e => {
      const b = e.relatedTarget;
      document.getElementById('edit_madh').value = b.getAttribute('data-madh');
      document.getElementById('edit_tendh').value = b.getAttribute('data-tendh');
      document.getElementById('edit_gpa').value = b.getAttribute('data-gpa') ?? '';
      document.getElementById('edit_drl').value = b.getAttribute('data-drl') ?? '';
      document.getElementById('edit_ntn').value = b.getAttribute('data-ntn') ?? '';
    });

    // Modal Xóa
    const delModal = document.getElementById('modalDelete');
    delModal?.addEventListener('show.bs.modal', e => {
      const b = e.relatedTarget;
      document.getElementById('del_madh').value = b.getAttribute('data-madh');
      document.getElementById('del_tendh').textContent = b.getAttribute('data-tendh');
    });
  });
</script>
@endpush
@endsection