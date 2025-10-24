<?php
// app/Http/Controllers/SinhVienController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SinhVien;

class SinhVienController extends Controller
{
    public function index(Request $r)
    {
        $matk = session('auth.MaTK') ?? session('user.MaTK');

        if (!$matk) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
                'ntnItems'      => collect(),     
                'awardProgress' => collect(),     
            ]);
        }

        $sv = DB::table('BANG_SinhVien')->where('MaTK', $matk)->first();
        if (!$sv) {
            return view('sinhvien.index', [
                'sv'       => null,
                'gpaVal'   => null,
                'drlVal'   => null,
                'ngaySinh' => null,
                'ntnTong'  => 0,
                'awds'     => collect(),
                'goiY'     => null,
                'ntnItems'      => collect(),     
        'awardProgress' => collect(),     
    ]);
        }

        $masv = $sv->MaSV;

        $gpa = DB::table('BANG_DiemHocTap')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $gpaVal = $gpa->DiemHe4 ?? null;

        $drl = DB::table('BANG_DiemRenLuyen')
            ->where('MaSV', $masv)
            ->orderByDesc('NamHoc')
            ->orderByDesc('HocKy')
            ->first();
        $drlVal = $drl->DiemRL ?? null;

        // Tổng số ngày tình nguyện đã duyệt (đã dùng đúng cột SoNgayTN & TrangThaiDuyet)
        $ntnTong = DB::table('BANG_NgayTinhNguyen')
            ->where('MaSV', $masv)
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->selectRaw('COALESCE(SUM(SoNgayTN), 0) AS tong')
            ->value('tong') ?? 0;

        // Định dạng ngày sinh cho view (tùy có/không)
        $ngaySinh = null;
        if (!empty($sv->NgaySinh)) {
            try {
                $ngaySinh = Carbon::parse($sv->NgaySinh)->format('Y-m-d');
            } catch (\Throwable $e) {
                $ngaySinh = $sv->NgaySinh;
            }
        }

        // Khen thưởng đã nhận
        // TÍNH DANH HIỆU GIỐNG TRANG ĐOÀN TRƯỜNG (không cần AwardRules)
        $labels = $this->DanhHieuDatDuoc($masv);

        // Chuẩn hoá cho Blade: collection object có field Ten
        $awds = collect($labels)->map(fn($ten) => (object)[
    'Ten'   => $ten,
    'HocKy' => 'HK1'
]);

        // (Tuỳ chọn) gợi ý danh hiệu, nếu có logic thì set, không thì null
        $goiY = null;

        // 8) GỢI Ý DANH HIỆU — chỉ dựa trên NGÀY TÌNH NGUYỆN còn thiếu (1–3 ngày)
        //    Điều kiện: GPA và DRL đã đạt, NTN chưa đạt nhưng thiếu <= 3 ngày.
        $danhhieu = DB::table('BANG_DanhHieu')
        ->select('TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN')
        ->get();
        $goiY = [];
        foreach ($danhhieu as $dh) {
            $reqGpa = (float)($dh->DieuKienGPA ?? 0);
            $reqDrl = (int)($dh->DieuKienDRL ?? 0);
            $reqNtn = (int)($dh->DieuKienNTN ?? 0);

            $okGpa = (float)($gpaVal ?? 0) >= $reqGpa;
            $okDrl = (int)($drlVal ?? 0)  >= $reqDrl;

            if (!$okGpa || !$okDrl) {
                // Chưa đủ GPA/DRL thì không gợi ý
                continue;
            }

            $thieu = $reqNtn - (int)$ntnTong;
            if ($thieu > 0 && $thieu <= 3) {
                $goiY[] = "Bạn còn thiếu {$thieu} ngày tình nguyện để đạt danh hiệu {$dh->TenDH}.";
            }
        }

        $danhhieu = DB::table('BANG_DanhHieu')
        ->select('TenDH', 'DieuKienNTN')
        ->orderBy('TenDH')
        ->get();

    $awardProgress = $danhhieu->map(function ($dh) use ($ntnTong) {
        $req = (int)($dh->DieuKienNTN ?? 0);
        // tránh chia cho 0: nếu không quy định NTN, coi như đạt 100
        if ($req <= 0) {
            return (object)[
                'ten'  => $dh->TenDH,
                'req'  => 0,
                'cur'  => (int)$ntnTong,
                'pct'  => 100,
            ];
        }
        $pct = min(100, (int) round($ntnTong * 100 / $req));
        return (object)[
            'ten'  => $dh->TenDH,
            'req'  => $req,
            'cur'  => (int)$ntnTong,
            'pct'  => $pct,
        ];
    });

    return view('sinhvien.index', [
            'sv'       => $sv,
            'gpaVal'   => $gpaVal,
            'drlVal'   => $drlVal,
            'ngaySinh' => $ngaySinh,
            'ntnTong'  => (int)($ntnTong ?? 0),
            'ntnItems' => $ntnItems ?? collect(),
            'awds'     => $awds ?? collect(),
            'goiY'     => $goiY,
            'awardProgress' => $awardProgress,
        ]);
    }
    /**
        * Hàm tính danh hiệu đạt được của sinh viên
     */
    private function DanhHieuDatDuoc(string $maSV): array
    {
        // Lấy các chỉ số hiện tại của SV
        $gpa = DB::table('BANG_DiemHocTap')
            ->where('MaSV', $maSV)
            ->select(DB::raw('MAX(DiemHe4) as DiemHe4'))
            ->value('DiemHe4') ?? 0;

        $drl = DB::table('BANG_DiemRenLuyen')
            ->where('MaSV', $maSV)
            ->select(DB::raw('MAX(DiemRL) as DiemRL'))
            ->value('DiemRL') ?? 0;

        $ntn = DB::table('BANG_NgayTinhNguyen')
            ->where('MaSV', $maSV)
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->select(DB::raw('SUM(SoNgayTN) as SoNgayTN'))
            ->value('SoNgayTN') ?? 0;

        // Lấy danh sách danh hiệu và điều kiện
        $danhhieu = DB::table('BANG_DanhHieu')->get();

        // Tính các danh hiệu thoả điều kiện
        $labels = [];
        foreach ($danhhieu as $d) {
            $okGPA = $gpa >= ($d->DieuKienGPA ?? 0);
            $okDRL = $drl >= ($d->DieuKienDRL ?? 0);
            $okNTN = $ntn >= ($d->DieuKienNTN ?? 0);

            if ($okGPA && $okDRL && $okNTN) {
                $labels[] = $d->TenDH;
            }
        }
        return $labels;
    }
}

