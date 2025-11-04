@extends('layouts.app')
@section('title', 'Phòng Khảo thí | Danh sách sinh viên')

@section('content')
<link rel="stylesheet" href="{{ asset('css/khaothi.css') }}">
<h5 class="mb-3">Danh sách sinh viên</h5>

<div class="khaothi-toolbar card mb-3">
  <form method="get" action="">
    <input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên / Lớp">
    <button type="submit" class="btn btn-outline-primary btn-animate ripple">
      <i class="bi bi-search"></i> Tìm
    </button>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:60px">STT</th>
        <th>MSSV</th>
        <th>Họ và Tên</th>
        <th>Ngày sinh</th>
        <th>Khoa</th>
        <th>Lớp</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($data as $i => $r)
      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>{{ $r->NgaySinh }}</td>
        <td>{{ $r->Khoa }}</td>
        <td>{{ $r->Lop }}</td>
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
@push('scripts')
<script>
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