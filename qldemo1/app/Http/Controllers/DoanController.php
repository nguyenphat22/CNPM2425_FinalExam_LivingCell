<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhenThuongExport;
use App\Models\NgayTinhNguyen;
use App\Models\DanhHieu;

class DoanController extends Controller
{
    // ========================= Khen thưởng danh hiệu =========================
public function khenThuongIndex(Request $r)
{
    $hk = $r->input('hk', 'HK1-2024-2025');
    $q  = trim((string) $r->input('q', ''));

    // 1) Lọc sinh viên theo MSSV/Họ tên trước (nếu có q)
    $svQuery = DB::table('BANG_SinhVien')->select('MaSV', 'HoTen');
    if ($q !== '') {
        $svQuery->where(function($w) use ($q){
            $w->where('MaSV', 'like', "%{$q}%")
              ->orWhere('HoTen', 'like', "%{$q}%");
        });
    }
    $sinhvien = $svQuery->orderBy('MaSV')->get();

    // 2) Lấy GPA/DRL/NTN (của toàn bộ SV trong danh sách trên)
    $listMaSV = $sinhvien->pluck('MaSV')->all();

    $gpa = DB::table('BANG_DiemHocTap')
        ->whereIn('MaSV', $listMaSV)
        ->select('MaSV', DB::raw('MAX(DiemHe4) as DiemHe4'))
        ->groupBy('MaSV')->pluck('DiemHe4', 'MaSV');

    $drl = DB::table('BANG_DiemRenLuyen')
        ->whereIn('MaSV', $listMaSV)
        ->select('MaSV', DB::raw('MAX(DiemRL) as DiemRL'))
        ->groupBy('MaSV')->pluck('DiemRL', 'MaSV');

    $ntn = DB::table('BANG_NgayTinhNguyen')
        ->whereIn('MaSV', $listMaSV)
        ->where('TrangThaiDuyet', 'DaDuyet')
        ->select('MaSV', DB::raw('SUM(SoNgayTN) as SoNgayTN'))
        ->groupBy('MaSV')->pluck('SoNgayTN', 'MaSV');

    // 3) Điều kiện danh hiệu
    $danhhieu = DB::table('BANG_DanhHieu')->get();

    // 4) Tính danh hiệu đạt được
    $rows = [];
    foreach ($sinhvien as $sv) {
        $ma = $sv->MaSV;
        $labels = [];

        foreach ($danhhieu as $d) {
            $okGPA = ($gpa[$ma] ?? 0) >= ($d->DieuKienGPA ?? 0);
            $okDRL = ($drl[$ma] ?? 0) >= ($d->DieuKienDRL ?? 0);
            $okNTN = ($ntn[$ma] ?? 0) >= ($d->DieuKienNTN ?? 0);

            if ($okGPA && $okDRL && $okNTN) {
                $labels[] = $d->TenDH;
            }
        }

        $rows[] = (object)[
            'MaSV'     => $sv->MaSV,
            'HoTen'    => $sv->HoTen,
            'DanhHieu' => $labels ? implode(', ', $labels) : null,
        ];
    }

    // 5) Nếu người dùng tìm theo tên danh hiệu → lọc thêm ở PHP
    if ($q !== '') {
        $qLower = mb_strtolower($q, 'UTF-8');
        $rows = array_values(array_filter($rows, function($row) use ($qLower){
            // Khớp MSSV / Họ tên / Danh hiệu (danh hiệu đã lọc MSSV/Họ tên ở SQL rồi nhưng giữ lại cho đầy đủ)
            return mb_strpos(mb_strtolower($row->MaSV, 'UTF-8'), $qLower) !== false
                || mb_strpos(mb_strtolower($row->HoTen, 'UTF-8'), $qLower) !== false
                || ($row->DanhHieu && mb_strpos(mb_strtolower($row->DanhHieu, 'UTF-8'), $qLower) !== false);
        }));
    }

    // Chuyển sang collection cho tiện render
    $data = collect($rows);

    return view('doan.khenthuong', [
        'data' => $data,
        'hk'   => $hk,
        'q'    => $q,
    ]);
}
public function exportExcel(Request $r)
{
    $hk = $r->input('hk', 'HK1-2024-2025');
    $fileName = "Bao_cao_KhenThuong_{$hk}.xlsx";
    return Excel::download(new KhenThuongExport($hk), $fileName);
}

    // ========================= Ngày tình nguyện =========================

    public function tinhNguyenIndex(Request $r)
    {
        $q = trim((string) $r->input('q', ''));

        $query = DB::table('BANG_SinhVien as sv')
            ->leftJoin('bang_ngaytinhnguyen as ntn', 'sv.MaSV', '=', 'ntn.MaSV')
            ->select(
                'ntn.MaNTN',
                'sv.MaSV',
                'sv.HoTen',
                'ntn.TenHoatDong',
                'ntn.NgayThamGia',
                'ntn.SoNgayTN',
                'ntn.TrangThaiDuyet'
            );

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->where('sv.MaSV', 'like', "%{$q}%")
                    ->orWhere('sv.HoTen', 'like', "%{$q}%")
                    ->orWhere('ntn.TenHoatDong', 'like', "%{$q}%");
            });
        }

        // ---- SẮP XẾP GIỐNG CTCT-HSSV ----
        // Nếu bên CTCT chỉ dùng orderBy('sv.MaSV'), bạn dùng như vậy là đủ.
        // Để chuẩn hơn khi MSSV có dấu '.', sort theo giá trị số:
        $query->orderByRaw('LPAD(REPLACE(sv.MaSV, ".", ""), 20, "0")');

        $data = $query->paginate(10)->withQueryString();

        // nếu bạn cần list MSSV trong modal Thêm:
        $dsSV = DB::table('BANG_SinhVien')
            ->orderByRaw('LPAD(REPLACE(MaSV, ".", ""), 20, "0")')
            ->select('MaSV', 'HoTen')
            ->get();

        return view('doan.tinhnguyen', compact('data', 'q', 'dsSV'));
    }
    public function ntnStore(Request $r)
    {
        $r->validate([
            'MaSV'         => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'  => 'required|string|max:200',
            'NgayThamGia'  => 'required|date',
            'SoNgayTN'     => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ], [], [
            'MaSV' => 'MSSV'
        ]);

        DB::table('bang_ngaytinhnguyen')->insert([
            'MaSV'          => $r->MaSV,
            'TenHoatDong'   => $r->TenHoatDong,
            'NgayThamGia'   => $r->NgayThamGia,
            'SoNgayTN'      => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã thêm hoạt động tình nguyện.');
    }

    public function ntnUpdate(Request $r)
    {
        $r->validate([
            'MaNTN'        => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
            'MaSV'         => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'  => 'required|string|max:200',
            'NgayThamGia'  => 'required|date',
            'SoNgayTN'     => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ]);

        DB::table('bang_ngaytinhnguyen')->where('MaNTN', $r->MaNTN)->update([
            'MaSV'          => $r->MaSV,
            'TenHoatDong'   => $r->TenHoatDong,
            'NgayThamGia'   => $r->NgayThamGia,
            'SoNgayTN'      => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã cập nhật hoạt động.');
    }

    public function ntnDelete(Request $r)
    {
        $r->validate([
            'MaNTN' => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
        ]);

        DB::table('bang_ngaytinhnguyen')->where('MaNTN', $r->MaNTN)->delete();

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã xoá hoạt động.');
    }

    // ========== Import Excel (.xlsx/.xls/.csv) ==========
    public function ntnImport(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new \App\Imports\NtnImport, $r->file('file'));
        } catch (QueryException $e) {
            return back()->withErrors('Import lỗi: ' . $e->getMessage());
        } catch (\Throwable $e) {
            return back()->withErrors('Import lỗi: ' . $e->getMessage());
        }

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Nhập danh sách hoạt động TN thành công.');
    }

    // Trang danh sách danh hiệu
    public function danhHieuIndex(Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = trim((string) $r->input('q', ''));

        $data = DB::table('bang_danhhieu')
            ->when($q !== '', fn($s) => $s->where('TenDH', 'like', "%{$q}%"))
            ->select('MaDH', 'TenDH', 'DieuKienGPA', 'DieuKienDRL', 'DieuKienNTN')
            ->orderBy('MaDH')
            ->paginate(10)
            ->withQueryString();

        return view('doan.danhhieu', compact('data', 'hk', 'nh', 'q'));
    }

    // Thêm danh hiệu
    public function dhStore(Request $r)
    {
        $ten = (string) \Illuminate\Support\Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u', ' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
    'TenDH'       => 'required|string|max:100|unique:bang_danhhieu,TenDH',
    'DieuKienGPA' => 'required|numeric|min:0|max:4',
    'DieuKienDRL' => 'required|integer|min:0|max:100',
    'DieuKienNTN' => 'required|integer|min:0',
], [
    'TenDH.required'       => 'Vui lòng nhập Tên danh hiệu.',
    'TenDH.unique'         => 'Tên danh hiệu đã tồn tại.',
    'DieuKienGPA.required' => 'Vui lòng nhập GPA.',
    'DieuKienDRL.required' => 'Vui lòng nhập điểm rèn luyện.',
    'DieuKienNTN.required' => 'Vui lòng nhập số ngày tình nguyện.',
]);

        try {
            DB::table('bang_danhhieu')->insert([
                'TenDH'       => $r->TenDH,
                'DieuKienGPA' => $r->DieuKienGPA,
                'DieuKienDRL' => $r->DieuKienDRL,
                'DieuKienNTN' => $r->DieuKienNTN,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['TenDH' => 'Tên danh hiệu đã tồn tại.'])->withInput();
            }
            throw $e;
        }

        // ✅ QUAN TRỌNG: luôn redirect về trang danh sách sau khi POST
        return redirect()
            ->route('doan.danhhieu.index')
            ->with('ok', 'Đã thêm danh hiệu.');
    }

    // Cập nhật danh hiệu
    public function dhUpdate(Request $r)
    {
        $ten = (string) Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u', ' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
            'MaDH'        => 'required|integer|exists:bang_danhhieu,MaDH',
            'TenDH'       => [
                'required',
                'string',
                'max:100',
                Rule::unique('bang_danhhieu', 'TenDH')->ignore($r->MaDH, 'MaDH'),
            ],
            'DieuKienGPA' => 'nullable|numeric|min:0|max:4',
            'DieuKienDRL' => 'nullable|integer|min:0|max:100',
            'DieuKienNTN' => 'nullable|integer|min:0',
        ], [
            'TenDH.unique' => 'Tên danh hiệu đã tồn tại.',
        ], [
            'TenDH' => 'Tên danh hiệu',
        ]);

        DB::table('bang_danhhieu')
            ->where('MaDH', $r->MaDH)
            ->update([
                'TenDH'       => $r->TenDH,
                'DieuKienGPA' => $r->DieuKienGPA,
                'DieuKienDRL' => $r->DieuKienDRL,
                'DieuKienNTN' => $r->DieuKienNTN,
            ]);

        return redirect()->route('doan.danhhieu.index')->with('ok', 'Đã cập nhật danh hiệu.');
    }

    // Xoá danh hiệu
    public function dhDelete(Request $r)
    {
        $r->validate([
            'MaDH' => 'required|integer|exists:bang_danhhieu,MaDH',
        ]);

        DB::table('bang_danhhieu')->where('MaDH', $r->MaDH)->delete();

        return redirect()->route('doan.danhhieu.index')->with('ok', 'Đã xóa danh hiệu.');
    }
}
