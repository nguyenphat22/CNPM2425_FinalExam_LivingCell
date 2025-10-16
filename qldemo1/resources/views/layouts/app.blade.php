<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','QLSV')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

{{-- Nút mở sidebar trên mobile (tùy chọn) --}}
<button class="btn btn-dark d-lg-none sidebar-toggler">☰</button>

<div class="sidebar-backdrop d-lg-none"></div>

<div class="d-flex app-wrap">
  {{-- SIDEBAR --}}
  <aside class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <div class="brand mb-3">QLSV</div>
    <ul class="nav nav-pills flex-column gap-2 mb-auto">
      <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.accounts.*')) active @endif"
           href="{{ route('admin.accounts.index') }}">
          Danh sách tài khoản
        </a>
      </li>
    </ul>

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
