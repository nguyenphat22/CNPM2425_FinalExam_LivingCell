<ul class="nav nav-pills flex-column gap-1">
  <li class="nav-item">
    <a href="{{ route('admin.accounts.index') }}"
       class="nav-link {{ request()->routeIs('admin.accounts.*') ? 'active' : 'text-white' }}">
      Danh sách tài khoản
    </a>
  </li>
</ul>
