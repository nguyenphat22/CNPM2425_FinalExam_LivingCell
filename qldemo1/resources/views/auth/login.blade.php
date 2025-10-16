@extends('layouts.guest')
@section('title','Đăng nhập')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">ĐĂNG NHẬP</div>
      <div class="card-body">
        @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

        <form method="POST" action="{{ route('login.submit') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input type="text"
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
            <label class="form-label">Mật khẩu</label>
            <input type="password"
              name="MatKhau"
              class="form-control @error('MatKhau') is-invalid @enderror"
              placeholder="Nhập mật khẩu"
              required>
            @error('MatKhau')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            {{-- Nếu có lỗi đăng nhập sai tên/mật khẩu --}}
            @if ($errors->any() && !$errors->has('TenDangNhap') && !$errors->has('MatKhau'))
            <div class="invalid-feedback d-block">
              {{ $errors->first() }}
            </div>
            @endif
          </div>

          <button class="btn btn-primary w-100">Đăng nhập</button>
        </form>


        <div class="text-center mt-3">
          <a href="{{ route('forgot.show') }}">Quên mật khẩu?</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection