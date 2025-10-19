{{-- resources/views/sinhvien/index.blade.php --}}
@extends('layouts.app')
@section('title','Sinh viên')

@section('content')
@php
// Chuẩn hoá số liệu hiển thị
$gpaNow = (float)($gpaVal ?? 0);
$drlNow = (int)($drlVal ?? 0);
// Ngày tình nguyện quy về thang 100 (để vẽ tiến độ); ntnTong đã là tổng ngày đã duyệt
$ntnNow = min((int)($ntnTong ?? 0), 100);

$gpaPct = max(0, min(100, round(($gpaNow/4) * 100)));
$drlPct = max(0, min(100, round(($drlNow/100) * 100)));
$ntnPct = max(0, min(100, round(($ntnNow/100) * 100)));
@endphp

<div class="row">
  <main class="col-md-12">
    <h5 class="mb-3">Thông tin cá nhân</h5>
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

    <div class="row g-3">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="fw-bold mb-1">Điểm học tập (GPA)</div>
            <div class="fs-5">{{ $gpaVal ?? '—' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="fw-bold mb-1">Điểm rèn luyện</div>
            <div class="fs-5">{{ $drlVal ?? '—' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="fw-bold mb-1">Số ngày tình nguyện</div>
            <div class="fs-5">{{ $ntnTong }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Khen thưởng danh hiệu --}}
    <h5 class="mb-2 text-center" style="color:green;">Khen thưởng danh hiệu</h5>
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
            <td class="text-center">
              <span class="badge rounded-pill text-bg-success">{{ $a->Ten }}</span>
            </td>
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
    <h5 class="mt-4 mb-2 text-center" style="color:green;">Đề xuất danh hiệu</h5>
    <div class="card mb-3">
      <div class="card-body">
        @if(!empty($goiY))
        <ul class="mb-0">
          @foreach($goiY as $msg)
          <li>{{ $msg }}</li>
          @endforeach
        </ul>
        @else
        Hiện tại không có danh hiệu phù hợp, bạn hãy cố gắng hơn nhé!
        @endif
      </div>
    </div>

    {{-- TIẾN ĐỘ: thanh progress + doughnut --}}
    <h5 class="mt-2 mb-3 text-center text-primary">Xem tiến độ</h5>
    <div class="row g-4 align-items-center justify-content-center">
      {{-- Cột progress --}}
      <div class="col-lg-7 col-md-8">
        {{-- GPA --}}
        <div class="mb-3">
          <div class="d-flex justify-content-between small mb-1">
            <span>Điểm học tập (GPA): {{ number_format($gpaNow, 2) }} / 4.00</span>
            <span class="fw-semibold">{{ $gpaPct }}%</span>
          </div>
          <div class="progress" style="height:18px">
            <div class="progress-bar bg-success js-w" data-w="{{ $gpaPct }}"></div>
          </div>
        </div>

        {{-- DRL --}}
        <div class="mb-3">
          <div class="d-flex justify-content-between small mb-1">
            <span>Điểm rèn luyện: {{ $drlNow }} / 100</span>
            <span class="fw-semibold">{{ $drlPct }}%</span>
          </div>
          <div class="progress" style="height:18px">
            <div class="progress-bar bg-success js-w" data-w="{{ $drlPct }}"></div>
          </div>
        </div>

        {{-- NTN --}}
        <div class="mb-3">
          <div class="d-flex justify-content-between small mb-1">
            <span>Ngày tình nguyện: {{ $ntnNow }} / 100</span>
            <span class="fw-semibold">{{ $ntnPct }}%</span>
          </div>
          <div class="progress" style="height:18px">
            <div class="progress-bar bg-success js-w" data-w="{{ $ntnPct }}"></div>
          </div>
        </div>
      </div>

      {{-- Biểu đồ tròn --}}
      <div class="col-lg-4 col-md-5">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h6 class="mb-3 text-primary fw-bold">Biểu đồ tiến độ</h6>
            <canvas id="svProgressDonut"
              data-gpa="{{ $gpaPct }}"
              data-drl="{{ $drlPct }}"
              data-ntn="{{ $ntnPct }}"
              height="160"></canvas>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
  /* Transition mượt cho progress-bar */
  .progress-bar.js-w {
    width: 0;
    transition: width .8s ease;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Đặt width cho các progress-bar theo data-w
    document.querySelectorAll('.progress-bar.js-w').forEach(el => {
      const w = parseInt(el.getAttribute('data-w') || '0', 10);
      // setTimeout để đảm bảo transition chạy
      setTimeout(() => el.style.width = (w + '%'), 80);
    });

    // Doughnut chart
    const cvs = document.getElementById('svProgressDonut');
    if (cvs) {
      const gpa = +cvs.dataset.gpa || 0;
      const drl = +cvs.dataset.drl || 0;
      const ntn = +cvs.dataset.ntn || 0;

      new Chart(cvs, {
        type: 'doughnut',
        data: {
          labels: ['GPA', 'Rèn luyện', 'Tình nguyện'],
          datasets: [{
            data: [gpa, drl, ntn],
            backgroundColor: ['#166534', '#2e7d32', '#edc948'],
            borderWidth: 0
          }]
        },
        options: {
          cutout: '58%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                boxWidth: 18,
                usePointStyle: true
              }
            },
            tooltip: {
              enabled: true
            }
          }
        }
      });
    }
  });
</script>
@endpush