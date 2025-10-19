@extends('layouts.app')
@section('title','Admin')

@section('content')
<h4 class="mb-3">Trang Admin</h4>
<div class="list-group">
  <a href="{{ route('admin.accounts.index') }}" class="list-group-item list-group-item-action">
    Danh sách tài khoản
  </a>
  {{-- Sau này có thể thêm menu khác tại đây --}}
</div>
@endsection