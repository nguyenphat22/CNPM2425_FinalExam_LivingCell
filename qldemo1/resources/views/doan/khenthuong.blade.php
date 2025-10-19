@extends('layouts.app')
@section('title','Danh sách khen thưởng sinh viên')

@section('content')
<h5 class="mb-3">Danh sách khen thưởng sinh viên</h5>

<div class="d-flex mb-3 gap-2">
  {{-- Học kỳ (giữ lại giá trị khi submit) --}}
  <form id="hk-form" class="d-flex gap-2" method="get" action="{{ route('doan.khenthuong.index') }}">
    <select class="form-select" style="width:180px" name="hk" onchange="document.getElementById('hk-form').submit()">
      <option value="HK1-2024-2025" {{ $hk==='HK1-2024-2025' ? 'selected':'' }}>HK1-2024-2025</option>
      <option value="HK2-2024-2025" {{ $hk==='HK2-2024-2025' ? 'selected':'' }}>HK2-2024-2025</option>
      {{-- ... nếu có thêm học kỳ khác --}}
    </select>
    {{-- Khi đổi HK vẫn giữ q nếu đang có --}}
    @if(!empty($q))
    <input type="hidden" name="q" value="{{ $q }}">
    @endif
  </form>
  {{-- 👉 Nút xuất Excel --}}
  <a href="{{ route('doan.khenthuong.export', ['hk' => $hk]) }}" class="btn btn-success">
    Xuất file Excel
  </a>

  {{-- Form tìm kiếm --}}
  <form class="ms-auto d-flex" method="get" action="{{ route('doan.khenthuong.index') }}">
    <input type="hidden" name="hk" value="{{ $hk }}">
    <input class="form-control me-2" name="q" value="{{ $q ?? '' }}" placeholder="Tìm MSSV / Họ tên / Danh hiệu">
    <button class="btn btn-outline-primary" type="submit">Tìm</button>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px">STT</th>
        <th style="width:120px">MSSV</th>
        <th>Họ và Tên</th>
        <th>Danh hiệu đạt được</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $i => $r)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>
          @if ($r->DanhHieu)
          <span class="badge bg-success">{{ $r->DanhHieu }}</span>
          @else
          <span class="text-muted">—</span>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center text-muted">Không có dữ liệu.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection