@extends('layouts.guest')
@section('title','Quên mật khẩu')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">QUÊN MẬT KHẨU</div>
      <div class="card-body">
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
            <div class="invalid-feedback">{{ $message }}</div>
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
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Lỗi xác thực cặp tài khoản + email không khớp --}}
          @if ($errors->has('credentials'))
          <div class="invalid-feedback d-block mb-2">
            {{ $errors->first('credentials') }}
          </div>
          @endif

          <button class="btn btn-primary w-100">Xác nhận</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection