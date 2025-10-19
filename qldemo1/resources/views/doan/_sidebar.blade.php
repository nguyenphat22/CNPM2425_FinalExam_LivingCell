<ul class="nav nav-pills flex-column gap-2">
  <li class="nav-item">
    <a href="{{ route('doan.khenthuong.index') }}"
      class="nav-link {{ request()->routeIs('doan.khenthuong.index') ? 'active' : 'text-white' }}">
      Danh sách khen thưởng SV
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('doan.tinhnguyen.index') }}"
      class="nav-link {{ request()->routeIs('doan.tinhnguyen.index') ? 'active' : 'text-white' }}">
      Quản lý ngày tình nguyện
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('doan.danhhieu.index') }}"
      class="nav-link {{ request()->routeIs('doan.danhhieu.index') ? 'active' : 'text-white' }}">
      Quản lý danh hiệu
    </a>
  </li>
</ul>