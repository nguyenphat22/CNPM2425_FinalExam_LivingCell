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
            <input name="TenDangNhap" class="form-control" required value="{{ old('TenDangNhap') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="MatKhau" class="form-control" required>
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
