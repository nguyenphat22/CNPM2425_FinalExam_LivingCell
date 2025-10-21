<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','QLSV')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- CSS custom (không dùng Vite) --}}
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

  <style>
    .app-wrap{min-height:100vh;}

    /* Toggle btn (desktop) */
    #btnToggleSidebar{
      background:#1f2a37; opacity:.7; border-top-right-radius:8px; border-bottom-right-radius:8px;
      transition:opacity .2s, background-color .2s;
    }
    #btnToggleSidebar:hover{ opacity:1; background:#0d6efd; }

    /* Offcanvas mobile */
    @media (max-width: 991.98px){
      .sidebar{ position:fixed; z-index:1030; transform:translateX(-100%); transition:.2s; border-radius:0; }
      .sidebar.show{ transform:none; }
      .sidebar-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.35); display:none; z-index:1029; }
      .sidebar-backdrop.show{ display:block; }
      .sidebar-toggler{ position:fixed; top:12px; left:12px; z-index:1031; }
    }
  </style>
</head>

<body>
  {{-- Nút 3 chấm toggle sidebar trên desktop --}}
  <button id="btnToggleSidebar"
    class="btn btn-dark d-none d-lg-flex align-items-center justify-content-center position-fixed top-0 start-0 mt-3"
    style="width:44px;height:36px;z-index:2000;">
    <i class="bi bi-three-dots fs-5"></i>
  </button>

  {{-- Nút mở sidebar trên mobile --}}
  <button class="btn btn-dark d-lg-none sidebar-toggler">☰</button>
  <div class="sidebar-backdrop d-lg-none"></div>

  <div class="d-flex app-wrap">
    {{-- SIDEBAR --}}
    <aside class="sidebar text-white p-3 d-flex flex-column">
      <div class="brand text-center mb-3">
        <img src="{{ asset('assets/images/logo_truong.png') }}" alt="Logo" class="logo">
      </div>

      {{-- Thông tin người dùng (nếu muốn) --}}
      @php $u = session('user'); @endphp
      <div class="user-info text-center mb-2">
        <i class="bi bi-person fs-2 d-block mb-1"></i>
        <div class="fw-semibold">{{ $u['name'] ?? 'Administrator' }}</div>
        <div class="opacity-75 small">{{ $u['role'] ?? 'null' }}</div>
        <div class="opacity-75 small">{{ $u['email'] ?? 'null' }}</div>
      </div>

      {{-- Menu theo module --}}
      <nav class="mb-auto w-100 mt-2">
        @if (request()->is('admin*'))
          @include('admin._sidebar')
        @elseif (request()->is('sinhvien*'))
          @include('sinhvien._sidebar')
        @elseif (request()->is('ctct*'))
          @include('ctct._sidebar')
        @elseif (request()->is('khaothi*'))
          @include('khaothi._sidebar')
        @elseif (request()->is('doantruong*'))
          @include('doan._sidebar')
        @endif
      </nav>

      <form method="post" action="{{ route('logout') }}" class="mt-3 w-100">
        @csrf
        <button class="btn btn-light w-100 fw-semibold">Đăng xuất</button>
      </form>
    </aside>

    {{-- CONTENT --}}
    <main class="flex-grow-1 d-flex flex-column">
      {{-- Header xám nhạt + tiêu đề hệ thống --}}
      <header class="bg-light border-bottom text-center py-2">
        <div class="small text-uppercase fw-semibold" style="letter-spacing:.3px">
          HỆ THỐNG NGHIỆP VỤ CÔNG TÁC RÈN LUYỆN SINH VIÊN
        </div>
        <div class="small text-uppercase text-secondary" style="letter-spacing:.3px">
          TRƯỜNG ĐẠI HỌC SƯ PHẠM THÀNH PHỐ HỒ CHÍ MINH
        </div>
      </header>

      <section class="p-4 flex-grow-1">
        @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

        {{-- Lỗi chung --}}
        @if($errors->any() && (!$errors->getBag('add')->any() && !$errors->getBag('edit')->any()))
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
          </div>
        @endif

        @yield('content')
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar   = document.querySelector('.sidebar');
    const backdrop  = document.querySelector('.sidebar-backdrop');
    const btn       = document.querySelector('.sidebar-toggler');
    const desktopBtn= document.getElementById('btnToggleSidebar');

    desktopBtn?.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });

    function toggleSidebar(show){
      if (!sidebar) return;
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
