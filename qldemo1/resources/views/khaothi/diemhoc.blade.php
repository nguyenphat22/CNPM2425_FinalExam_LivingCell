@extends('layouts.app')
@section('title','Khảo thí | Quản lý điểm học tập')

@section('content')
<link rel="stylesheet" href="{{ asset('css/khaothi-gpa.css') }}">
<h5 class="mb-3">Quản lý điểm học tập</h5>

<div class="d-flex gap-2 mb-3 align-items-center">
  <form method="post" action="{{ route('khaothi.gpa.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
    @csrf
    <input type="file" name="file" class="form-control" style="max-width:260px;" accept=".xlsx,.xls,.csv" required>
    <button class="btn btn-soft-primary btn-animate ripple">
  <i class="bi bi-cloud-upload me-1"></i> Upload file
</button>
  </form>

    {{-- Nút Mẫu Excel --}}
  <a href="{{ route('khaothi.gpa.template') }}"
     class="btn btn-soft-success btn-animate ripple">
    <i class="bi bi-file-earmark-excel me-1"></i> Mẫu Excel
  </a>

    {{-- Xuất báo cáo  --}}
  <a class="btn btn-soft-info btn-animate ripple"
    href="{{ route('khaothi.gpa.export', ['hk'=>$hk,'nh'=>$nh,'q'=>$q]) }}">
     <i class="bi bi-file-earmark-excel me-1"></i> Xuất báo cáo Excel
  </a>

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
    <input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên">
    <button class="btn btn-outline-primary btn-animate ripple btn-search" type="submit">
    Tìm
</button>
  </form>
</div>

@if (session('failures'))
<div class="alert alert-warning">
  <div class="fw-bold">Một số dòng không hợp lệ:</div>
  <ul class="mb-0">
    @foreach (session('failures') as $f)
    <li>Dòng {{ $f->row() }}: {{ implode('; ', $f->errors()) }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:60px">STT</th>
        <th style="width:140px">MSSV</th>
        <th>Họ và Tên</th>
        <th style="width:140px">Điểm học tập</th>
        <th style="width:140px">Xếp loại</th>
        <th style="width:140px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>{{ $r->DiemHT ?? '' }}</td>
        <td>{{ $r->XepLoai ?? '' }}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
            data-bs-toggle="modal" data-bs-target="#modalEdit"
            data-masv="{{ $r->MaSV }}"
            data-hk="{{ $hk }}"
            data-nh="{{ $nh }}"
            data-diem="{{ $r->DiemHT ?? '' }}"
            data-xeploai="{{ $r->XepLoai ?? '' }}">Sửa</button>

          <button class="btn btn-sm btn-outline-danger btn-animate ripple"
            data-bs-toggle="modal" data-bs-target="#modalDelete"
            data-masv="{{ $r->MaSV }}"
            data-hk="{{ $hk }}"
            data-nh="{{ $nh }}">Xóa</button>
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

{{-- MODAL SỬA --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('khaothi.gpa.update') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Sửa điểm học tập</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2"><label class="form-label">MSSV</label>
          <input class="form-control" name="MaSV" id="edit_masv" readonly>
        </div>
        <div class="mb-2"><label class="form-label">Học kỳ</label>
          <input class="form-control" name="HocKy" id="edit_hk" readonly>
        </div>
        <div class="mb-2"><label class="form-label">Năm học</label>
          <input class="form-control" name="NamHoc" id="edit_nh" readonly>
        </div>
        <div class="mb-2"><label class="form-label">Điểm học tập</label>
          <input type="number" step="0.01" min="0" max="4" class="form-control" name="DiemHT" id="edit_diem" required>
        </div>
        <div class="mb-2"><label class="form-label">Xếp loại</label>
          <input class="form-control" name="XepLoai" id="edit_xeploai">
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Lưu</button></div>
    </form>
  </div>
</div>

{{-- MODAL XÓA --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('khaothi.gpa.delete') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Xóa điểm học tập</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="MaSV" id="del_masv_input">
        <input type="hidden" name="HocKy" id="del_hk_input">
        <input type="hidden" name="NamHoc" id="del_nh_input">
        Bạn chắc chắn muốn xóa GPA của MSSV <b id="del_masv_text"></b>
        (HK: <b id="del_hk_text"></b>, NH: <b id="del_nh_text"></b>)?
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
    // Nút Lưu: thông báo rồi quay lại trang chính
    document.getElementById('btn-refresh')?.addEventListener('click', () => {
      const alert = document.createElement('div');
      alert.className = 'alert alert-success position-fixed top-0 end-0 m-3 shadow';
      alert.style.zIndex = '2000';
      alert.textContent = '✅ Đã cập nhật thành công! Đang quay lại...';
      document.body.appendChild(alert);
      setTimeout(() => {
        window.location.href = "{{ url('/khaothi/gpa') }}";
      }, 1500);
    });

    // === Hàm xếp loại theo thang 4 (BẢNG YÊU CẦU) ===
    function xepLoaiGPA(v) {
      // v có thể là chuỗi -> đổi sang số
      const s = Number(v);
      if (!Number.isFinite(s)) return ''; // chưa nhập -> để trống
      if (s >= 3.6 && s <= 4.0) return 'Xuất sắc';
      if (s >= 3.2) return 'Giỏi';
      if (s >= 2.5) return 'Khá';
      if (s >= 2.0) return 'Trung bình';
      if (s >= 1.0) return 'Yếu';
      if (s >= 0) return 'Kém';
      return '';
    }

    // Modal Sửa
    const editModal = document.getElementById('modalEdit');
    editModal?.addEventListener('show.bs.modal', ev => {
      const b = ev.relatedTarget;

      // đổ dữ liệu ban đầu
      document.getElementById('edit_masv').value = b.getAttribute('data-masv');
      document.getElementById('edit_hk').value = b.getAttribute('data-hk');
      document.getElementById('edit_nh').value = b.getAttribute('data-nh');

      const diem = b.getAttribute('data-diem') ?? '';
      document.getElementById('edit_diem').value = diem;
      // nếu có điểm thì tính xếp loại, nếu trống -> để trống
      document.getElementById('edit_xeploai').value = diem === '' ? '' : xepLoaiGPA(diem);

      // auto cập nhật xếp loại khi người dùng gõ/chỉnh điểm
      const onChange = () => {
        const v = document.getElementById('edit_diem').value;
        document.getElementById('edit_xeploai').value = (v === '' ? '' : xepLoaiGPA(v));
      };
      document.getElementById('edit_diem').removeEventListener('input', onChange);
      document.getElementById('edit_diem').addEventListener('input', onChange);
      document.getElementById('edit_diem').addEventListener('change', onChange);
    });

    // Modal Xóa
    const delModal = document.getElementById('modalDelete');
    delModal?.addEventListener('show.bs.modal', ev => {
      const b = ev.relatedTarget;
      const masv = b.getAttribute('data-masv');
      const hk = b.getAttribute('data-hk');
      const nh = b.getAttribute('data-nh');
      document.getElementById('del_masv_input').value = masv;
      document.getElementById('del_hk_input').value = hk;
      document.getElementById('del_nh_input').value = nh;
      document.getElementById('del_masv_text').textContent = masv;
      document.getElementById('del_hk_text').textContent = hk;
      document.getElementById('del_nh_text').textContent = nh;
    });
  });
</script>
@endpush
@endsection