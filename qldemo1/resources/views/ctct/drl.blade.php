{{-- resources/views/ctct/drl.blade.php --}}
@extends('layouts.app')
@section('title','CTCT-HSSV | Quản lý điểm rèn luyện')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ctct-drl.css') }}">
<div class="row">
  <main class="col-md-12">
    <h5 class="mb-3">Quản lý điểm rèn luyện</h5>

    {{-- Thanh công cụ --}}
    <div class="d-flex gap-2 mb-3 align-items-center">
      {{-- Import Excel (chưa làm thì có thể tắt) --}}
      <form method="post" action="{{ route('ctct.drl.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="file" class="form-control" style="max-width:260px;" accept=".xlsx,.xls,.csv" required>
        <button class="btn btn-soft-secondary btn-animate ripple">
  <i class="bi bi-cloud-upload me-1"></i> Upload file
</button>
      </form>
{{--  Mẫu Excel DRL --}}
  <a href="{{ route('ctct.drl.template') }}"
     class="btn btn-soft-success btn-animate ripple">
    <i class="bi bi-file-earmark-excel me-1"></i> Mẫu Excel
  </a>
      {{-- Xuất báo cáo  --}}
      <a class="btn btn-soft-info btn-animate ripple"
   href="{{ route('ctct.drl.export', ['hk'=>$hk, 'nh'=>$nh, 'q'=>$q]) }}">
  <i class="bi bi-file-earmark-excel me-1"></i> Xuất báo cáo Excel
</a>


      {{-- Nút Lưu (hiển thị toast + refresh) --}}
        <button id="btn-refresh" class="btn btn-soft-warning btn-animate ripple" type="button" onclick="showSaveMessage()">
  <i class="bi-check-circle"></i> Lưu
</button>

      {{-- Bộ lọc + tìm kiếm --}}
      <form class="ms-auto d-flex gap-2" method="get">
        <select class="form-select" name="hk" style="width:160px">
          <option value="1" {{ (int)($hk ?? 1)===1 ? 'selected' : '' }}>HK1</option>
          <option value="2" {{ (int)($hk ?? 1)===2 ? 'selected' : '' }}>HK2</option>
          <option value="3" {{ (int)($hk ?? 1)===3 ? 'selected' : '' }}>HK Hè</option>
        </select>
        <input class="form-control" name="nh" value="{{ $nh ?? '2024-2025' }}" style="width:150px" placeholder="Năm học">
        <input class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Tìm MSSV, Họ tên..." style="width:220px">
        <button class="btn btn-outline-primary btn-animate ripple">Tìm</button>
      </form>
    </div>

    @if(session('failures'))
    <div class="alert alert-warning mt-2">
      <div class="fw-bold">Một số dòng không hợp lệ:</div>
      <ul class="mb-0">
        @foreach (session('failures') as $f)
        <li>Dòng {{ $f->row() }}: {{ implode('; ', $f->errors()) }}</li>
        @endforeach
      </ul>
    </div>
    @endif


    {{-- Bảng dữ liệu --}}
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:80px">STT</th>
            <th style="width:140px">MSSV</th>
            <th>Họ và Tên</th>
            <th style="width:160px">Điểm rèn luyện</th>
            <th style="width:160px">Xếp loại</th>
            <th style="width:140px">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $r)
          <tr>
            <td>{{ $data->firstItem() + $i }}</td>
            <td>{{ $r->MaSV }}</td>
            <td>{{ $r->HoTen }}</td>
            <td>{{ $r->DiemRL ?? '' }}</td>
            <td>{{ $r->XepLoai ?? '' }}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
                data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-masv="{{ $r->MaSV }}"
                data-hoten="{{ $r->HoTen }}"
                data-hk="{{ $hk }}" {{-- dùng HK/NH đang lọc --}}
                data-nh="{{ $nh }}"
                data-diem="{{ $r->DiemRL ?? '' }}"
                data-xeploai="{{ $r->XepLoai ?? '' }}">Sửa</button>

              <button type="button" class="btn btn-sm btn-outline-danger btn-animate ripple"
                data-bs-toggle="modal" data-bs-target="#modalDelete"
                data-masv="{{ $r->MaSV }}"
                data-hk="{{ $hk }}" data-nh="{{ $nh }}">Xóa</button>
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
  </main>
</div>

{{-- MODAL SỬA DRL --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('ctct.drl.update') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Sửa điểm rèn luyện</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <input class="form-control" name="MaSV" id="edit_masv" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Học kỳ</label>
          <input class="form-control" name="HocKy" id="edit_hk" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Năm học</label>
          <input class="form-control" name="NamHoc" id="edit_nh" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Điểm rèn luyện</label>
          <input type="number" min="0" max="100" class="form-control" name="DiemRL" id="edit_diem" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Xếp loại</label>
          <input class="form-control" name="XepLoai" id="edit_xeploai">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-animate ripple">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL XÓA DRL --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('ctct.drl.delete') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Xóa điểm rèn luyện</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="MaSV" id="del_masv_input">
        <input type="hidden" name="HocKy" id="del_hk_input">
        <input type="hidden" name="NamHoc" id="del_nh_input">
        Bạn chắc chắn muốn xóa DRL của MSSV <b id="del_masv_text"></b>
        (HK: <b id="del_hk_text"></b>, NH: <b id="del_nh_text"></b>)?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-animate ripple" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-danger btn-animate ripple">Xóa</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  /* === Quy tắc xếp loại theo điểm DRL === */
  function rankFromScore(s) {
    s = Number(s);
    if (Number.isNaN(s)) return '';
    s = Math.max(0, Math.min(100, s)); // chặn ngoài 0..100
    if (s >= 90) return 'Xuất sắc';
    if (s >= 80) return 'Tốt';
    if (s >= 65) return 'Khá';
    if (s >= 50) return 'Trung bình';
    if (s >= 35) return 'Yếu';
    return 'Kém';
  }

  /* Cập nhật xếp loại trong modal SỬA */
  function applyEditRank() {
    const scoreEl = document.getElementById('edit_diem');
    const rankEl = document.getElementById('edit_xeploai');
    if (!scoreEl || !rankEl) return;

    const val = scoreEl.value.trim();
    // Nếu chưa nhập gì → để trống xếp loại
    if (val === '') {
      rankEl.value = '';
      return;
    }

    rankEl.value = rankFromScore(val);
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Nút Lưu giả
    document.getElementById('btn-refresh')?.addEventListener('click', () => {
      const alert = document.createElement('div');
      alert.className = 'alert alert-success position-fixed top-0 end-0 m-3 shadow';
      alert.style.zIndex = '2000';
      alert.textContent = '✅ Đã lưu dữ liệu, đang quay lại trang chính...';
      document.body.appendChild(alert);
      setTimeout(() => {
        window.location.href = "{{ url('/ctct/drl') }}";
      }, 1500);
    });

    // Modal SỬA
    const editModal = document.getElementById('modalEdit');
    editModal?.addEventListener('show.bs.modal', ev => {
      const b = ev.relatedTarget;
      document.getElementById('edit_masv').value = b.getAttribute('data-masv');
      document.getElementById('edit_hk').value = b.getAttribute('data-hk');
      document.getElementById('edit_nh').value = b.getAttribute('data-nh');
      document.getElementById('edit_diem').value = b.getAttribute('data-diem') ?? '';
      document.getElementById('edit_xeploai').value = b.getAttribute('data-xeploai') ?? '';

      // Khi mở modal, tính lại xếp loại từ điểm hiện có
      applyEditRank();

      // Gắn sự kiện thay đổi điểm -> tự tính xếp loại
      const scoreEl = document.getElementById('edit_diem');
      scoreEl.removeEventListener('input', applyEditRank); // tránh gắn trùng
      scoreEl.removeEventListener('change', applyEditRank);
      scoreEl.addEventListener('input', applyEditRank);
      scoreEl.addEventListener('change', applyEditRank);
    });

    // Modal XÓA
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
<script>
  // Ripple effect cho mọi nút có .ripple
  document.addEventListener('click', function(e){
    const t = e.target.closest('.ripple'); if(!t) return;
    const r = t.getBoundingClientRect(), d = Math.max(r.width, r.height);
    const x = e.clientX - r.left - d/2, y = e.clientY - r.top - d/2;
    const ink = document.createElement('span');
    Object.assign(ink.style,{
      position:'absolute', borderRadius:'50%', pointerEvents:'none',
      width:d+'px', height:d+'px', left:x+'px', top:y+'px',
      background:'rgba(255,255,255,.35)', transform:'scale(0)',
      transition:'transform .35s ease, opacity .55s ease'
    });
    t.appendChild(ink);
    requestAnimationFrame(()=>{ ink.style.transform='scale(2.6)'; ink.style.opacity='0'; });
    setTimeout(()=>ink.remove(),520);
  });
</script>
@endpush
@endsection