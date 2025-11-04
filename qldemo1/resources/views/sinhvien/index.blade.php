{{-- resources/views/sinhvien/index.blade.php --}}
@extends('layouts.app')
@section('title','Sinh viên')
@section('content')
<link rel="stylesheet" href="{{ asset('css/sinhvien.css') }}">

{{-- Bảng điều khiển sinh viên --}}
<div class="row">
  <main class="col-md-12 hide-old-progress-link">
    {{-- Banner hiệu ứng gradient + typewriter --}}
<section class="hero-animated my-4 text-center">
  {{-- Đếm ký tự câu này (ví dụ 36), điền vào --chars --}}
  <div class="typewriter" style="--chars: 36">
    Chào mừng bạn đến với trang Sinh viên
  </div>
  <p class="hero-sub">Theo dõi GPA, điểm rèn luyện và tiến độ danh hiệu của bạn.</p>
  <a href="#progress" class="btn-neon mt-3">
    Xem tiến độ <i class="bi bi-lightning-charge"></i>
  </a>
</section>
    <h3 class="section-title underline glow with-icon">
  <span>Thông tin cá nhân</span>
  <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
    <i class="bi bi-gear" style="font-size: 1.4rem;"></i>
  </button>
</h3>

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
        <div class="card-apple simple">
          <div class="card-body">
            <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
            <div class="fs-5">{{ $gpaVal ?? '—' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-apple simple">
          <div class="card-body">
            <div class="fw-bold mb-1">Điểm rèn luyện</div>
            <div class="fs-5">{{ $drlVal ?? '—' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-apple simple">
          <div class="card-body">
            <div class="fw-bold mb-1">Số ngày tình nguyện</div>
            <div class="fs-5">{{ $ntnTong }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Danh hiệu --}}
    <h3 id="awards" class="section-title underline glow"><span>Khen thưởng danh hiệu</span></h3>
    <div class="table-responsive mb-3 awards-shell">
  <table class="table table-modern">
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
            <td class="text-center"> <span class="badge-award">{{ $a->Ten }}</span></td>
            <td>{{ $a->HocKy ?? '—' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="text-center text-muted">Chưa có khen thưởng.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Đề xuất --}}
    <h3 id="suggestions" class="section-title underline glow"><span>Đề xuất danh hiệu</span></h3>
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
<div class="text-center">
  <h3 id="progress" class="section-title centered glow"><span>Xem tiến độ</span></h3>
</div>
{{-- (xoá hoặc đổi link cũ như hướng dẫn ở trên) --}}

<div id="progress" class="card">
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
<!-- Modal đổi mật khẩu -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('sv.settings.password') }}" autocomplete="off">
          @csrf
          <div class="mb-3">
            <label class="form-label">Mật khẩu cũ</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
          </div>

          <button class="btn btn-primary" type="submit">Đổi mật khẩu</button>
        </form>
      </div>
    </div>
  </div>
</div>