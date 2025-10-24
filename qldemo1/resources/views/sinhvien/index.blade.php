{{-- resources/views/sinhvien/index.blade.php --}}
@extends('layouts.app')
@section('title','Sinh viên')
@section('content')
<link rel="stylesheet" href="{{ asset('css/sinhvien.css') }}">

{{-- Bảng điều khiển sinh viên --}}
<div class="row">
  <main class="col-md-12">
    <h5 class="mb-3">Thông tin cá nhân</h5>

    {{-- Thông tin sinh viên --}}
    <div class="card mb-3">
      <div class="card-body">
        @if($sv)
          <div><b>MSSV:</b> {{ $sv->MaSV }}</div>
          <div><b>Họ và Tên:</b> {{ $sv->HoTen }}</div>
          <div><b>Ngày sinh:</b> {{ $ngaySinh ?? '—' }}</div>
          <div><b>Lớp:</b> {{ $sv->Lop ?? '—' }}</div>
          <div><b>Khoa:</b> {{ $sv->Khoa ?? '—' }}</div>
        @else
          <em>Chưa liên kết thông tin sinh viên với tài khoản này.</em>
        @endif
      </div>
    </div>

    {{-- Ba cột thông tin điểm --}}
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
          <div class="fs-5">{{ $gpaVal ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Điểm rèn luyện</div>
          <div class="fs-5">{{ $drlVal ?? '—' }}</div>
        </div></div>
      </div>
      <div class="col-md-4">
        <div class="card"><div class="card-body">
          <div class="fw-bold mb-1">Số ngày tình nguyện</div>
          <div class="fs-5">{{ $ntnTong }}</div>
        </div></div>
      </div>
    </div>

    {{-- Danh hiệu --}}
    <h5 class="mb-2 text-center text-success">Khen thưởng danh hiệu</h5>
    <div class="table-responsive mb-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:70px">STT</th>
            <th class="text-center">Danh hiệu đạt được</th>
            <th style="width:160px">Học kỳ</th>
          </tr>
        </thead>
        <tbody>
          @forelse($awds as $i => $a)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td class="text-center"><span class="badge rounded-pill text-bg-success">{{ $a->Ten }}</span></td>
              <td>{{ $a->HocKy ?? '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">Chưa có khen thưởng.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Đề xuất --}}
    <h5 class="mt-4 mb-2 text-center text-success">Đề xuất danh hiệu</h5>
    <div class="card mb-3">
  <div class="card-body">
    @if(!empty($goiY))
      <ul class="mb-0">
        @foreach($goiY as $msg)
          <li>{{ $msg }}</li>
        @endforeach
      </ul>
    @else
      <div class="no-award-box">
        Hiện tại không có danh hiệu phù hợp, bạn hãy cố gắng hơn nhé!
      </div>
    @endif
  </div>
</div>

    {{-- ===== XEM TIẾN ĐỘ ===== --}}
    <h5 class="mt-4 mb-3 text-center text-primary fw-semibold">Xem tiến độ</h5>
<a href="#" class="btn-view-progress">Xem tiến độ</a>

    <div class="card">
      <div class="card-body">
        @php($awardProgress = $awardProgress ?? collect())
        @forelse($awardProgress as $ap)
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span>{{ $ap->ten }}</span>
              @if($ap->req > 0)
                <span class="fw-semibold">{{ $ap->cur }} / {{ $ap->req }} ngày</span>
              @else
                <span class="fw-semibold">Không yêu cầu ngày tình nguyện</span>
              @endif
            </div>

            {{-- Thanh tiến độ --}}
            <div class="progress height-22">
              <div 
                class="progress-bar {{ $ap->pct >= 100 ? 'bg-success' : 'bg-warning' }}"
                role="progressbar"
                data-width="{{ $ap->pct }}"
                aria-valuenow="{{ $ap->pct }}" aria-valuemin="0" aria-valuemax="100">
                {{ $ap->pct }}%
              </div>
            </div>
          </div>
        @empty
          <em class="text-muted">Chưa có danh hiệu nào được cấu hình.</em>
        @endforelse
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  // Animation cho progress bar
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.progress-bar').forEach(el => {
      const w = parseInt(el.dataset.width || '0');
      el.style.width = '0%';
      setTimeout(() => el.style.width = w + '%', 100);
    });
  });
</script>
@endpush