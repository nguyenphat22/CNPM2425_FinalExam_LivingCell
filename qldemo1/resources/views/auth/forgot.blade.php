@extends('layouts.guest')
@section('title','Quên mật khẩu')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">QUÊN MẬT KHẨU</div>
      <div class="card-body">
        <form method="POST" action="{{ route('forgot.handle') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Tên đăng nhập</label>
            <input name="TenDangNhap" class="form-control" required value="{{ old('TenDangNhap') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email công tác</label>
            <input name="Email" type="email" class="form-control" required value="{{ old('Email') }}">
          </div>
          <button class="btn btn-primary w-100">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
