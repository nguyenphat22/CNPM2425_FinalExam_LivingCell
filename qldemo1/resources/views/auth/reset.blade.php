@extends('layouts.guest')
@section('title','Đổi mật khẩu')

@section('content')
<div class="auth-wrapper">
  {{-- CỘT TRÁI --}}
  <div class="auth-left">
    {{-- Logo + Tên trường (1 ảnh gộp) --}}
    <div class="brand-combo">
      <img src="{{ asset('assets/images/logo_truong.png') }}" alt="HCMUE Logo & Name">
    </div>

    {{-- Logo Đoàn & Hội --}}
    <div class="club">
      <img src="{{ asset('assets/images/logo_doan.png') }}" alt="Logo Đoàn">
      <img src="{{ asset('assets/images/logo_hoi.png') }}" alt="Logo Hội SV">
    </div>

    {{-- Tiêu đề hệ thống --}}
    <div class="title">
      HỆ THỐNG NGHIỆP VỤ<br>
      CÔNG TÁC RÈN LUYỆN SINH VIÊN
    </div>

    {{-- Khung đổi mật khẩu --}}
    <div class="card-box">
      <div class="text-center fw-bold mb-2" style="font-size:14px;">ĐỔI MẬT KHẨU</div>

      @if(session('ok'))
        <div class="alert alert-success mb-3 py-2 text-center">{{ session('ok') }}</div>
      @endif

      <form method="POST" action="{{ route('reset.handle') }}" novalidate>
        @csrf

        <div class="mb-3">
          <label class="form-label">Mật khẩu mới</label>
          <input type="password" name="MatKhau"
                 class="form-control @error('MatKhau') is-invalid @enderror"
                 placeholder="Nhập mật khẩu mới" required>
          @error('MatKhau')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Xác nhận mật khẩu</label>
          <input type="password" name="MatKhau_confirmation"
                 class="form-control @error('MatKhau_confirmation') is-invalid @enderror"
                 placeholder="Nhập lại mật khẩu" required>
          @error('MatKhau_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <button class="btn btn-primary w-100">Xác nhận</button>

        <div class="text-center mt-3">
          <a href="{{ route('login.show') }}" class="forgot-link">← Quay lại đăng nhập</a>
        </div>
      </form>
    </div>

   <div class="footer-note">
      ©2025 Hệ thống QLRLKTSV. Developed by
      <a href="https://github.com/nguyenphat22/CNPM2425_FinalExam_LivingCell" target="_blank">
        <img src="{{ asset('assets/images/logo_dark.png') }}" alt="Living Cell Logo">
      </a>
    </div>
  </div>

  {{-- CỘT PHẢI --}}
  <div class="auth-right"></div>
</div>
@endsection
