@extends('layouts.guest')
@section('title','Đổi mật khẩu')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header text-center fw-bold">ĐỔI MẬT KHẨU</div>
      <div class="card-body">
        @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
        <form method="POST" action="{{ route('reset.handle') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" name="MatKhau" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="MatKhau_confirmation" class="form-control" required>
          </div>
          <button class="btn btn-success w-100">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection