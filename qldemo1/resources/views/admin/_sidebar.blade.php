<div class="text-white-50 small mb-2 ps-1">Quản trị hệ thống</div>
<a class="nav-link {{ request()->routeIs('admin.accounts.index') ? 'active' : '' }}"
   href="{{ route('admin.accounts.index') }}">
  <i class="bi bi-people me-1"></i> <span>Danh sách tài khoản</span>
</a>

