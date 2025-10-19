<ul class="nav nav-pills flex-column gap-2">
  <li class="nav-item">
    <a href="{{ route('khaothi.sinhvien.index') }}"
      class="nav-link {{ request()->routeIs('khaothi.sinhvien.index') ? 'active' : 'text-white' }}">
      Danh sách sinh viên
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('khaothi.gpa.index') }}"
      class="nav-link {{ request()->routeIs('khaothi.gpa.index') ? 'active' : 'text-white' }}">
      Quản lý điểm học tập
    </a>
  </li>
</ul>