@extends('layouts.guest')
@section('title','Đăng nhập')

@section('content')
<div class="auth-wrapper">
  {{-- CỘT TRÁI --}}
  <div class="auth-left">
    {{-- Logo trường + tên trường (1 ảnh gộp) --}}
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

    {{-- Khung đăng nhập --}}
    <div class="card-box">
      @if(session('ok'))
        <div class="alert alert-success mb-3 py-2">{{ session('ok') }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input type="text" name="TenDangNhap" value="{{ old('TenDangNhap') }}"
                 class="form-control @error('TenDangNhap') is-invalid @enderror"
                 placeholder="Username" required>
          @error('TenDangNhap')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Mật khẩu</label>
          <input type="password" name="MatKhau"
                 class="form-control @error('MatKhau') is-invalid @enderror"
                 placeholder="Password" required>
          @error('MatKhau')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
          @if ($errors->any() && !$errors->has('TenDangNhap') && !$errors->has('MatKhau'))
            <div class="invalid-feedback d-block mt-1">{{ $errors->first() }}</div>
          @endif
        </div>

        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

        <div class="text-center mt-3 mb-1">
          <a href="{{ route('forgot.show') }}" class="forgot-link">Quên mật khẩu?</a>
        </div>
      </form>
    </div>

   <div class="footer-note">
  ©2025 Hệ thống QLRLKTSV. Developed by 
  <a href="https://github.com/nguyenphat22/CNPM2425_FinalExam_LivingCell" target="_blank" class="dev-logo">
    <img src="{{ asset('assets/images/logo_dark.png') }}" alt="Living Cell Logo">
  </a>
</div>
  </div>

  {{-- CỘT PHẢI --}}
  <div class="auth-right"></div>
</div>
@endsection
