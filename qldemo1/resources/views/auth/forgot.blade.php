@extends('layouts.guest')
@section('title','Quên mật khẩu')

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

    {{-- Card nhập thông tin --}}
    <div class="card-box">
      <div class="text-center fw-bold mb-2" style="font-size:14px;">QUÊN MẬT KHẨU</div>

      <form method="POST" action="{{ route('forgot.handle') }}" novalidate>
        @csrf

        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input
            type="text"
            name="TenDangNhap"
            value="{{ old('TenDangNhap') }}"
            class="form-control @error('TenDangNhap') is-invalid @enderror"
            placeholder="Nhập tên đăng nhập"
            required>
          @error('TenDangNhap')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email công tác</label>
          <input
            type="email"
            name="Email"
            value="{{ old('Email') }}"
            class="form-control @error('Email') is-invalid @enderror"
            placeholder="name@yourdomain.edu.vn"
            required>
          @error('Email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        {{-- Lỗi xác thực tài khoản + email không khớp --}}
        @if ($errors->has('credentials'))
          <div class="invalid-feedback d-block mb-2">
            {{ $errors->first('credentials') }}
          </div>
        @endif

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
