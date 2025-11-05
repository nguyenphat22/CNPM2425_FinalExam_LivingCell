<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','QLSV')</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- CSS custom (không dùng Vite) --}}
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

  <style>
    .app-wrap {
      min-height: 100vh;
    }

    /* Toggle btn (desktop) */
    #btnToggleSidebar {
      background: #1f2a37;
      opacity: .7;
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
      transition: opacity .2s, background-color .2s;
    }

    #btnToggleSidebar:hover {
      opacity: 1;
      background: #0d6efd;
    }

    /* Offcanvas mobile */
    @media (max-width: 991.98px) {
      .sidebar {
        position: fixed;
        z-index: 1030;
        transform: translateX(-100%);
        transition: .2s;
        border-radius: 0;
      }

      .sidebar.show {
        transform: none;
      }

      .sidebar-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .35);
        display: none;
        z-index: 1029;
      }

      .sidebar-backdrop.show {
        display: block;
      }

      .sidebar-toggler {
        position: fixed;
        top: 12px;
        left: 12px;
        z-index: 1031;
      }
    }

    @media (min-width: 992px) {
      .app-wrap {
        align-items: flex-start;
      }

      /* để sticky hoạt động đúng */
      .sidebar {
        position: sticky;
        /* quan trọng */
        top: 0;
        /* mốc bám */
        height: 100vh;
        /* cao full màn hình */
        overflow-y: auto;
        /* nếu menu dài thì chỉ sidebar tự cuộn */
        flex: 0 0 260px;
        /* giữ bề rộng cố định */
      }
    }
  </style>
  <style>
    /* Nền của sidebar và text */
    :root {
      --sidebar-bg: #164B71;
      /* màu panel chính */
      --sidebar-hover: #1d5a8d;
      /* sáng hơn một chút cho hover */
      --sidebar-active: #164B71;
      /* trùng hẳn với nền panel */
    }

    /* Giữ nền link đồng nhất với panel */
    .sidebar nav .nav-link {
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
      overflow: hidden;
      border-radius: 8px;
      color: #fff;
      background: transparent;
      /* mặc định trong suốt */
      transition: background .2s ease, color .2s ease;
    }

    /* Hover: sáng nhẹ hơn */
    .sidebar nav .nav-link:hover {
      background: var(--sidebar-hover);
    }

    /* Active: trùng màu panel (hoặc sáng hơn chút) */
    .sidebar nav .nav-link.active {
      background: var(--sidebar-active);
      font-weight: 600;
    }

    /* Giữ cho text trượt ngang mượt */
    .sidebar nav .nav-link span {
      display: inline-block;
      overflow: hidden;
      white-space: nowrap;
      max-width: 220px;
      opacity: 1;
      transition: max-width .25s ease, opacity .25s ease;
    }

    body.sidebar-collapsed .sidebar nav .nav-link span {
      max-width: 0;
      opacity: 0;
    }

    .sidebar nav .nav-link i {
      flex: 0 0 22px;
      display: grid;
      place-items: center;
    }

    /* (tuỳ chọn) mờ dần khi đóng */
    body.sidebar-collapsed .sidebar nav .nav-link {
      opacity: .0;
      transition: opacity .2s ease;
    }

    body:not(.sidebar-collapsed) .sidebar nav .nav-link {
      opacity: 1;
      transition: opacity .2s ease;
    }

    /* ===== Logout button co ngang như menu ===== */
    .sidebar form .btn {
      white-space: nowrap;
      overflow: hidden;
      min-height: 40px;
      /* tránh rung chiều cao */
      border-radius: 8px;
    }

    /* Chữ trong nút: co NGANG + mờ dần khi thu gọn */
    .sidebar form .btn .btn-text {
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      max-width: 220px;
      /* mở: hiển thị đầy đủ */
      opacity: 1;
      transition: max-width .25s ease, opacity .25s ease;
      will-change: max-width, opacity;
    }

    /* Đóng: chỉ còn icon, chữ co về 0 */
    body.sidebar-collapsed .sidebar form .btn .btn-text {
      max-width: 0;
      opacity: 0;
    }

    /* (tuỳ chọn) Icon cố định kích thước để không bị co */
    .sidebar form .btn i {
      flex: 0 0 20px;
      display: grid;
      place-items: center;
    }

    /* Slot icon cố định để chữ luôn thẳng hàng */
    .sidebar nav .nav-link i {
      flex: 0 0 22px;
      display: grid;
      place-items: center;
      font-size: 1.05rem;
    }
  </style>

</head>


<body class="sidebar-collapsed">
{{-- Nút 3 chấm toggle sidebar trên desktop --}}
<!-- <button id="btnToggleSidebar"
      class="btn btn-dark d-none d-lg-flex align-items-center justify-content-center position-fixed top-0 start-0 mt-3"
      style="width:44px;height:36px;z-index:2000;">
      <i class="bi bi-three-dots fs-5"></i>
    </button> -->

{{-- Nút mở sidebar trên mobile --}}
<button class="btn btn-dark d-lg-none sidebar-toggler">☰</button>
<div class="sidebar-backdrop d-lg-none"></div>

<div class="d-flex app-wrap">
  {{-- SIDEBAR --}}
  <aside class="sidebar text-white p-3 d-flex flex-column" id="appSidebar">
    {{-- Nút thu gọn / mở rộng (3 chấm) --}}
    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Thu gọn/mở rộng sidebar">
  <span class="dot"></span><span class="dot"></span><span class="dot"></span>
</button>

    <div class="brand text-center mb-3">
      <a href="https://hcmue.edu.vn/"><img src="{{ asset('assets/images/logo_truong.png') }}" alt="Logo" class="logo"></a>
    </div>

    {{-- Thông tin người dùng --}}
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
      <button class="btn btn-light w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-box-arrow-right"></i>
        <span class="btn-text">Đăng xuất</span>
      </button>
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
  const btnMobile = document.querySelector('.sidebar-toggler');

  function toggleSidebar(show){
    const add = typeof show === 'boolean' ? show : !sidebar.classList.contains('show');
    sidebar.classList.toggle('show', add);
    backdrop.classList.toggle('show', add);
    document.body.style.overflow = add ? 'hidden' : '';
  }

  btnMobile?.addEventListener('click', (e)=>{ e.stopPropagation(); toggleSidebar(); });
  backdrop?.addEventListener('click', ()=> toggleSidebar(false));
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') toggleSidebar(false); });
</script>
@stack('scripts')
<script>
  (function () {
    // Nhớ trạng thái thu gọn trên desktop
    const KEY = 'sidebar-collapsed';
    if (localStorage.getItem(KEY) === '1') {
      document.body.classList.add('sidebar-collapsed');
    }

    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
      const toggle = () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(KEY, document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
      };
      toggleBtn.addEventListener('click', toggle);
      toggleBtn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
      });
    }
  })();
</script>
<script>
  (function() {
    // Bảng ánh xạ từ "từ khóa trong text" -> class icon Bootstrap Icons
    const ICON_MAP = [
      // ===== dùng includes() theo text tiếng Việt =====
      {
        k: 'danh sách tài khoản',
        icon: 'bi-people'
      },
      {
        k: 'sinh viên',
        icon: 'bi-mortarboard'
      },
      {
        k: 'danh sách sinh viên',
        icon: 'bi-people'
      },

      {
        k: 'điểm rèn luyện',
        icon: 'bi-clipboard-check'
      },
      {
        k: 'điểm học tập',
        icon: 'bi-mortarboard-fill'
      },

      {
        k: 'khen thưởng',
        icon: 'bi-trophy'
      },
      {
        k: 'danh hiệu',
        icon: 'bi-award'
      },
      {
        k: 'ngày tình nguyện',
        icon: 'bi-calendar-heart'
      },

      {
        k: 'quản trị',
        icon: 'bi-gear'
      },
      {
        k: 'báo cáo',
        icon: 'bi-file-earmark-bar-graph'
      },
      {
        k: 'thống kê',
        icon: 'bi-bar-chart'
      },
    ];

    // (tuỳ chọn) map theo pattern đường dẫn nếu muốn chắc hơn
    const HREF_MAP = [
      // Khaothi: tách riêng từng trang
      {
        test: /khaothi\/sinh-vien|khaothi\/sinhvien/i,
        icon: 'bi-people'
      },
      {
        test: /khaothi\/quan-ly-diem|khaothi\/diem|khaothi\/hoc-tap/i,
        icon: 'bi-mortarboard-fill'
      },

      // Doàn/CTCT... (các rule khác giữ nguyên hoặc chi tiết hoá tương tự)
      {
        test: /doan(truong)?\/khen/i,
        icon: 'bi-trophy'
      },
      {
        test: /doan(truong)?\/danh-hieu|danhhieu/i,
        icon: 'bi-award'
      },
      {
        test: /doan(truong)?\/tinh-nguyen|ngaytn|volunteer/i,
        icon: 'bi-calendar-heart'
      },
    ];

    function pickIcon(linkEl) {
      const href = (linkEl.getAttribute('href') || '').toLowerCase();
      const text = (linkEl.textContent || '').toLowerCase().trim();

      // Ưu tiên match theo HREF trước (ổn định hơn nếu text thay đổi)
      for (const r of HREF_MAP) {
        if (r.test.test(href)) return r.icon;
      }
      // Sau đó mới thử match theo TEXT
      for (const r of ICON_MAP) {
        if (text.includes(r.k)) return r.icon;
      }
      // Mặc định: chấm tròn
      return 'bi-dot';
    }

    function injectIcons() {
      document.querySelectorAll('.sidebar nav a.nav-link').forEach(a => {
        // Bỏ qua nếu đã có icon
        if (a.querySelector('i')) return;

        // Tạo icon
        const i = document.createElement('i');
        i.className = 'bi ' + pickIcon(a);

        // Chèn icon vào đầu link
        a.prepend(i);
      });
    }

    // chạy khi DOM sẵn sàng
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', injectIcons);
    } else {
      injectIcons();
    }
  })();
</script>
</html>