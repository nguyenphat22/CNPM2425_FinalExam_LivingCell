<ul class="nav nav-pills flex-column gap-1">
  <li class="nav-item">
    <a href="{{ route('ctct.sinhvien.index') }}"
       class="nav-link {{ request()->routeIs('ctct.sinhvien.index') ? 'active' : 'text-white' }}">
      Danh sách sinh viên
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('ctct.drl.index') }}"
       class="nav-link {{ request()->routeIs('ctct.drl.index') ? 'active' : 'text-white' }}">
      Quản lý điểm rèn luyện
    </a>
  </li>
</ul>
