@extends('layouts.app')
@section('title','Quên mật khẩu')
@section('content')
<h1>Quên mật khẩu</h1>
@if ($errors->any()) <div>{{ $errors->first() }}</div> @endif
@if (session('token')) <p>Token tạm: {{ session('token') }}</p> @endif

<form method="POST" action="{{ route('forgot.verify') }}">@csrf
  <div><label>Tên đăng nhập</label><input name="username" required></div>
  <div><label>Email công tác</label><input name="email" type="email" required></div>
  <button>Xác nhận</button>
</form>

<hr>
<h2>Đổi mật khẩu</h2>
<form method="POST" action="{{ route('password.reset') }}">@csrf
  <div><label>Token</label><input name="token" value="{{ session('token') }}"></div>
  <div><label>Mật khẩu mới</label><input type="password" name="password" required></div>
  <div><label>Nhập lại</label><input type="password" name="password_confirmation" required></div>
  <button>Xác nhận</button>
</form>
@endsection
