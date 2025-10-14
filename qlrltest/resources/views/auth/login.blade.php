@extends('layouts.app')
@section('title','Đăng nhập')
@section('content')
<h1>Đăng nhập</h1>
@if(session('ok')) <div>{{ session('ok') }}</div> @endif
@if ($errors->any()) <div>{{ $errors->first() }}</div> @endif
<form method="POST" action="{{ route('login.submit') }}">
  @csrf
  <div><label>Tên đăng nhập</label><input name="username" required></div>
  <div><label>Mật khẩu</label><input type="password" name="password" required></div>
  <button type="submit">Đăng nhập</button>
</form>
<p><a href="{{ route('forgot.form') }}">Quên mật khẩu</a></p>
@endsection
