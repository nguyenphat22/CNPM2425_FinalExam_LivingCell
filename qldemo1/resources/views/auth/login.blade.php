@extends('layouts.guest')
@section('title','Đăng nhập')

@section('content')
    <style>
        /* ===== Full-bleed, chiếm trọn màn hình, bỏ mọi khoảng đệm của layout ===== */
        html, body { height: 100%; margin: 0; }
        body { overflow: hidden; background: #111; }

        .login-root{
            position: fixed; inset: 0;           /* fill toàn bộ viewport, không bị container/layout chi phối */
            display: flex; width: 100vw; height: 100vh;
            background: #0f172a;                  /* viền nền đậm giống ảnh mẫu */
        }
        .login-right {
            flex: 1;
            background: url('{{ asset("storage/Toa nha A-hinh that.png") }}') center center / cover no-repeat;
            background-color: #fff; /* ← nếu hình chưa load thì vẫn nền trắng */
        }
        .login-left{
            width: 400px;                         /* bề ngang cột trái */
            background: #0d3a66;                  /* xanh đậm */
            color: #fff;
            padding: 26px 22px;
            display: flex; flex-direction: column; justify-content: space-between;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            box-shadow: 1px 0 0 #0a2f52 inset;    /* vạch phân cách dọc giống mẫu */
        }

        /* Khối nội dung giữa cột trái */
        .left-inner{ max-width: 360px; margin: 0 auto; width: 100%; }

        /* Logo trường */
        .logo-top{ display:block; margin: 2px auto 8px; max-width: 260px; width: 100%; }

        /* Tên trường */
        .school-title{
            text-align:center; text-transform:uppercase; line-height:1.35;
            font-weight:700; font-size:13px; margin-bottom:10px;
        }
        .school-title .en{ display:block; color:#cfe6ff; font-weight:600; font-size:12px; }

        /* Logo Đoàn – Hội */
        .logos{ display:flex; justify-content:center; gap:14px; margin:6px 0 14px; }
        .logos img{ height:48px; }

        /* Tiêu đề hệ thống */
        .system-title{
            text-align:center; font-weight:800; font-size:15px; line-height:1.55;
            text-transform:uppercase; margin-bottom:16px;
        }

        /* Form trong card trắng giống mẫu */
        .login-card{
            background:#ffffff; border-radius:8px; padding:18px;
            box-shadow: 0 1px 0 rgba(0,0,0,.06);
        }
        .login-card label{ font-weight:600; color:#0d3a66; }
        .login-card .form-control{
            border:1px solid #d3dbe7; border-radius:6px; padding:10px; font-size:14px; margin-bottom:14px;
        }
        .login-card .btn{
            width:100%; background:#0b3a6d; color:#fff; border:none; border-radius:6px;
            font-weight:700; padding:10px;
        }
        .login-card .btn:hover{ background:#072e5a; }
        .login-card .forgot{ text-align:right; margin-top:8px; }
        .login-card .forgot a{ color:#0b3a6d; font-size:13px; text-decoration:none; }
        .login-card .forgot a:hover{ text-decoration:underline; }

        /* Footer nhỏ cuối cột trái */
        .login-footer{ text-align:center; font-size:12px; color:#d7e3f7; opacity:.9; }

        /* Cột phải: ảnh tòa nhà phủ kín */
        .login-right{
            flex:1;
            background: url('{{ asset("images/picture.png") }}') center center / cover no-repeat;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Responsive: ẩn ảnh khi màn nhỏ, giữ form ở giữa */
        @media (max-width: 992px){
            .login-left{ width:100%; border-radius:0; }
            .login-right{ display:none; }
        }
    </style>

    <div class="login-root">
        {{-- CỘT TRÁI --}}
        <aside class="login-left">
            <div class="left-inner">
                {{-- Logo trường (dùng picture.png để dễ thay) --}}
                <img src="{{ asset('storage/logoHCMUE.png') }}" alt="Logo Trường" class="logo-top">


                {{-- Tên trường --}}


                {{-- Logo Đoàn & Hội (dùng picture.png để dễ thay) --}}
                <div class="logos">
                    <img src="{{ asset('storage/doantn.png') }}" alt="Logo Đoàn">
                    <img src="{{ asset('storage/hoisv.png') }}" alt="Logo Hội">
                </div>

                {{-- Tiêu đề hệ thống --}}
                <div class="system-title">
                    HỆ THỐNG NGHIỆP VỤ<br>
                    CÔNG TÁC RÈN LUYỆN SINH VIÊN
                </div>

                {{-- Card form --}}
                <div class="login-card">
                    @if(session('ok'))
                        <div class="alert alert-success py-2">{{ session('ok') }}</div>
                    @endif
                    @if ($errors->any() && !$errors->has('TenDangNhap') && !$errors->has('MatKhau'))
                        <div class="alert alert-danger py-2 mb-2">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}" novalidate>
                        @csrf
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="TenDangNhap" value="{{ old('TenDangNhap') }}"
                               class="form-control @error('TenDangNhap') is-invalid @enderror" placeholder="Username" required>
                        @error('TenDangNhap')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="MatKhau"
                               class="form-control @error('MatKhau') is-invalid @enderror" placeholder="Password" required>
                        @error('MatKhau')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        <button class="btn">Đăng nhập</button>

                        <div class="forgot">
                            <a href="{{ route('forgot.show') }}">Quên mật khẩu ?</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="login-footer">All Rights <b>NOT</b> Reserved — Developed by PSC</div>
        </aside>

        {{-- CỘT PHẢI: ẢNH TÒA NHÀ (dùng picture.png) --}}z
        <section class="login-right" aria-hidden="true"></section>
    </div>
@endsection
