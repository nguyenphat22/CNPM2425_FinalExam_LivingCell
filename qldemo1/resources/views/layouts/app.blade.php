<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','QLSV')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Sidebar trái: cao full màn hình, cố định khi cuộn */
    .app-wrap{min-height:100vh;}
    .sidebar{
      width:260px; min-height:100vh; position:sticky; top:0;
    }
    .sidebar .brand{font-weight:700; letter-spacing:.5px;}
    .sidebar .nav-link{color:#cfd8dc; border-radius:.6rem; }
    .sidebar .nav-link:hover{color:#fff; background:rgba(255,255,255,.07)}
    .sidebar .nav-link.active{color:#0d6efd; background:#fff}
    /* Ẩn sidebar thủ công trên desktop */
.sidebar.collapsed {
  width: 0 !important;
  padding: 0 !important;
  overflow: hidden;
  transition: all 0.3s ease;
}
main {
  transition: margin-left 0.3s ease;
}
main.expanded {
  margin-left: 0;
}
#btnToggleSidebar {
  background-color: #1f2a37;
  opacity: 0.7;
  transition: opacity 0.2s, background-color 0.2s;
}
#btnToggleSidebar:hover {
  opacity: 1;
  background-color: #0d6efd;
}
    @media (max-width: 991.98px){
      .sidebar{position:fixed; z-index:1030; transform:translateX(-100%); transition:.2s}
      .sidebar.show{transform:none}
      .sidebar-backdrop{
        position:fixed; inset:0; background:rgba(0,0,0,.35); display:none; z-index:1029
      }
      .sidebar-backdrop.show{display:block}
      .sidebar-toggler{position:fixed; top:12px; left:12px; z-index:1031}
    }
  </style>
</head>
<body>
  {{-- Nút 3 chấm toggle sidebar trên desktop --}}
<button id="btnToggleSidebar"
  class="btn btn-dark d-none d-lg-flex align-items-center justify-content-center
         position-fixed top-0 start-0 mt-3"
  style="width:44px;height:36px;border-top-right-radius:8px;border-bottom-right-radius:8px;z-index:2000;">
  <i class="bi bi-three-dots fs-5"></i>
</button>

{{-- Nút mở sidebar trên mobile (tùy chọn) --}}
<button class="btn btn-dark d-lg-none sidebar-toggler">☰</button>

<div class="sidebar-backdrop d-lg-none"></div>

<div class="d-flex app-wrap">
  {{-- SIDEBAR --}}
  <aside class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <br><br>
    <div class="brand mb-3">QLSV</div>
    {{-- Sidebar theo module --}}
<div class="mb-auto">
  @if (request()->is('admin*'))
    <div class="text-white-80">Quản trị hệ thống</div>
    @include('admin._sidebar')
    @elseif (request()->is('sinhvien*'))
    <div class="text-white-80">Trang Sinh viên</div>
  @include('sinhvien._sidebar')
  @elseif (request()->is('ctct*'))
    <div class="text-white-80">CTCT-HSSV</div>
    @include('ctct._sidebar')
  @elseif (request()->is('khaothi*'))
  <div class="text-white-80">Khảo thí</div>
    @include('khaothi._sidebar')
  @elseif (request()->is('doantruong*'))
  <div class="text-white-80">Đoàn Trường</div>
    @include('doan._sidebar')
  @endif
</div>


    <form method="post" action="{{ route('logout') }}" class="mt-3">
      @csrf
      <button class="btn btn-outline-light w-100">Đăng xuất</button>
    </form>
  </aside>

  {{-- CONTENT --}}
  <main class="flex-grow-1 p-4">
    @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

    {{-- Hiển thị lỗi chung (nếu có) --}}
    @if($errors->any() && (!$errors->getBag('add')->any() && !$errors->getBag('edit')->any()))
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle sidebar trên desktop
const desktopBtn = document.getElementById('btnToggleSidebar');
desktopBtn?.addEventListener('click', () => {
  sidebar.classList.toggle('collapsed');
  document.querySelector('main').classList.toggle('expanded');
});
  // Toggle sidebar trên mobile
  const sidebar = document.querySelector('.sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  const btn = document.querySelector('.sidebar-toggler');

  function toggleSidebar(show){
    if(!sidebar) return;
    const add = typeof show === 'boolean' ? show : !sidebar.classList.contains('show');
    sidebar.classList.toggle('show', add);
    backdrop.classList.toggle('show', add);
    document.body.style.overflow = add ? 'hidden' : '';
  }
  btn?.addEventListener('click', () => toggleSidebar());
  backdrop?.addEventListener('click', () => toggleSidebar(false));
</script>
@stack('scripts')
</body>
</html>
