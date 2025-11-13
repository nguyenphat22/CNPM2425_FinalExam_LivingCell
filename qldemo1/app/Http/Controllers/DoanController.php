<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhenThuongExport;
use App\Models\NgayTinhNguyen;
use App\Models\DanhHieu;
use App\Models\SinhVien;
use App\Models\DiemHocTap;
use App\Models\DiemRenLuyen;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Imports\NtnImport;


class DoanController extends Controller
{
/* ======================== Khen thưởng danh hiệu ======================== */
    public function khenThuongIndex(Request $r)
    {
        $hk = $r->input('hk', 'HK1-2024-2025');
        $q  = trim((string) $r->input('q', ''));

        // 1) Lọc SV trước
        $sinhvien = SinhVien::query()
            ->select('MaSV','HoTen')
            ->search($q)
            ->orderBy('MaSV')
            ->get();

        $listMaSV = $sinhvien->pluck('MaSV')->all();

        // 2) Lấy GPA/DRL/NTN (Eloquent)
        $gpa = DiemHocTap::maxGpaByStudent($listMaSV);          // [MaSV => GPA]
        $drl = DiemRenLuyen::maxDrlByStudent($listMaSV);         // [MaSV => DRL]
        $ntn = NgayTinhNguyen::sumApprovedByStudent($listMaSV);  // [MaSV => SoNgayTN]

        // 3) Điều kiện danh hiệu
        $danhhieu = DanhHieu::query()->get();

        // 4) Tính danh hiệu đạt
        $rows = [];
        foreach ($sinhvien as $sv) {
            $ma = $sv->MaSV;
            $labels = [];

            foreach ($danhhieu as $d) {
                $okGPA = ($gpa[$ma] ?? 0) >= (float)($d->DieuKienGPA ?? 0);
                $okDRL = ($drl[$ma] ?? 0) >= (int)($d->DieuKienDRL ?? 0);
                $okNTN = ($ntn[$ma] ?? 0) >= (int)($d->DieuKienNTN ?? 0);
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

        // 5) Nếu q có chứa tên danh hiệu → lọc thêm
        if ($q !== '') {
            $qLower = mb_strtolower($q, 'UTF-8');
            $rows = array_values(array_filter($rows, function ($row) use ($qLower) {
                return mb_stripos($row->MaSV, $qLower, 0, 'UTF-8') !== false
                    || mb_stripos($row->HoTen, $qLower, 0, 'UTF-8') !== false
                    || ($row->DanhHieu && mb_stripos($row->DanhHieu, $qLower, 0, 'UTF-8') !== false);
            }));
        }

        // 6) Phân trang Collection
        $data  = collect($rows);
        $page  = max(1, (int)$r->input('page', 1));
        $per   = 10;
        $total = $data->count();
        $items = $data->slice(($page - 1) * $per, $per)->values();

        $data = new LengthAwarePaginator(
            $items, $total, $per, $page,
            ['path' => $r->url(), 'query' => $r->query()]
        );

        return view('doan.khenthuong', compact('data','hk','q'));
    }
    public function exportExcel(Request $r)
    {
        $hk = $r->input('hk', 'HK1-2024-2025');
        $fileName = "Bao_cao_KhenThuong_{$hk}.xlsx";
        return Excel::download(new KhenThuongExport($hk), $fileName);
    }

    /* ======================== Ngày tình nguyện ======================== */
    public function tinhNguyenIndex(Request $r)
    {
        $q = trim((string) $r->input('q', ''));

        // dùng scopeSearch (join SV, select thêm HoTen)
        $query = NgayTinhNguyen::query()->search($q);

        // Sắp MSSV theo số (bỏ '.')
        $table = (new NgayTinhNguyen)->getTable();
        $query->orderByRaw('LPAD(REPLACE('.$table.'.MaSV, ".", ""), 20, "0")');

        $data = $query->paginate(10)->withQueryString();

        // danh sách SV cho modal Thêm
        $dsSV = \App\Models\SinhVien::query()
            ->orderByRaw('LPAD(REPLACE(MaSV, ".", ""), 20, "0")')
            ->select('MaSV','HoTen')
            ->get();

        return view('doan.tinhnguyen', compact('data','q','dsSV'));
    }

    public function ntnStore(Request $r)
    {
        $r->validate([
            'MaSV'           => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'    => 'required|string|max:200',
            'NgayThamGia'    => 'required|date',
            'SoNgayTN'       => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ], [], ['MaSV' => 'MSSV']);

        NgayTinhNguyen::create([
            'MaSV'           => $r->MaSV,
            'TenHoatDong'    => $r->TenHoatDong,
            'NgayThamGia'    => $r->NgayThamGia,
            'SoNgayTN'       => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã thêm hoạt động tình nguyện.');
    }

    public function ntnUpdate(Request $r)
    {
        $r->validate([
            'MaNTN'          => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
            'MaSV'           => 'required|string|max:20|exists:BANG_SinhVien,MaSV',
            'TenHoatDong'    => 'required|string|max:200',
            'NgayThamGia'    => 'required|date',
            'SoNgayTN'       => 'required|integer|min:1',
            'TrangThaiDuyet' => 'required|in:ChuaDuyet,DaDuyet,TuChoi',
        ]);

        $ntn = NgayTinhNguyen::findOrFail($r->MaNTN);
        $ntn->update([
            'MaSV'           => $r->MaSV,
            'TenHoatDong'    => $r->TenHoatDong,
            'NgayThamGia'    => $r->NgayThamGia,
            'SoNgayTN'       => $r->SoNgayTN,
            'TrangThaiDuyet' => $r->TrangThaiDuyet,
        ]);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã cập nhật hoạt động.');
    }

    public function ntnDelete(Request $r)
    {
        $r->validate([
            'MaNTN' => 'required|integer|exists:bang_ngaytinhnguyen,MaNTN',
        ]);

        NgayTinhNguyen::destroy($r->MaNTN);

        return redirect()->route('doan.tinhnguyen.index')->with('ok', 'Đã xoá hoạt động.');
    }

    // ========== Import Excel (.xlsx/.xls/.csv) ==========
    public function ntnImport(Request $r)
{
    $r->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ]);

    try {
        $import = new NtnImport();
        Excel::import($import, $r->file('file'));

        $inserted = $import->insertedCount();
        $fails    = $import->failures();

        if ($fails->isNotEmpty()) {
            $errs = [];
            /** @var \Maatwebsite\Excel\Validators\Failure $f */
            foreach ($fails as $f) {
                $errs[] = "Dòng {$f->row()}: " . implode(', ', $f->errors());
            }
            return back()
                ->with('ok', "Đã nhập: {$inserted} dòng. Bỏ qua: {$fails->count()} dòng.")
                ->withErrors($errs);
        }

        if ($inserted === 0) {
            return back()->withErrors(
                'Không có dòng nào được nhập. Hãy kiểm tra lại tiêu đề cột: masv, tenhoatdong, ngaythamgia, songaytn, trangthaiduyet.'
            );
        }

        return redirect()
            ->route('doan.tinhnguyen.index')
            ->with('ok', "Nhập danh sách hoạt động TN thành công. Thêm mới: {$inserted} dòng.");
    } catch (QueryException $e) {
        return back()->withErrors('Import lỗi DB: ' . $e->getMessage());
    } catch (\Throwable $e) {
        return back()->withErrors('Import lỗi: ' . $e->getMessage());
    }
}

    /* ======================== Danh hiệu ======================== */
    public function danhHieuIndex(Request $r)
    {
        $hk = (int) $r->input('hk', 1);
        $nh = (string) $r->input('nh', '2024-2025');
        $q  = trim((string) $r->input('q', ''));

        $data = DanhHieu::query()
            ->search($q)
            ->select('MaDH','TenDH','DieuKienGPA','DieuKienDRL','DieuKienNTN')
            ->orderBy('MaDH')
            ->paginate(10)
            ->withQueryString();

        return view('doan.danhhieu', compact('data','hk','nh','q'));
    }

    public function dhStore(Request $r)
    {
        $ten = (string) \Illuminate\Support\Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u',' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
            'TenDH'       => 'required|string|max:100|unique:bang_danhhieu,TenDH',
            'DieuKienGPA' => 'required|numeric|min:0|max:4',
            'DieuKienDRL' => 'required|integer|min:0|max:100',
            'DieuKienNTN' => 'required|integer|min:0',
        ]);

        DanhHieu::create([
            'TenDH'       => $r->TenDH,
            'DieuKienGPA' => $r->DieuKienGPA,
            'DieuKienDRL' => $r->DieuKienDRL,
            'DieuKienNTN' => $r->DieuKienNTN,
        ]);

        return redirect()->route('doan.danhhieu.index')->with('ok','Đã thêm danh hiệu.');
    }

    public function dhUpdate(Request $r)
    {
        $ten = (string) \Illuminate\Support\Str::of($r->TenDH)->trim()->replaceMatches('/\s+/u',' ');
        $r->merge(['TenDH' => $ten]);

        $r->validate([
            'MaDH'        => 'required|integer|exists:bang_danhhieu,MaDH',
            'TenDH'       => ['required','string','max:100', Rule::unique('bang_danhhieu','TenDH')->ignore($r->MaDH,'MaDH')],
            'DieuKienGPA' => 'nullable|numeric|min:0|max:4',
            'DieuKienDRL' => 'nullable|integer|min:0|max:100',
            'DieuKienNTN' => 'nullable|integer|min:0',
        ], [
            'TenDH.unique' => 'Tên danh hiệu đã tồn tại.',
        ], [
            'TenDH' => 'Tên danh hiệu',
        ]);

        $dh = DanhHieu::findOrFail($r->MaDH);
        $dh->update([
            'TenDH'       => $r->TenDH,
            'DieuKienGPA' => $r->DieuKienGPA,
            'DieuKienDRL' => $r->DieuKienDRL,
            'DieuKienNTN' => $r->DieuKienNTN,
        ]);

        return redirect()->route('doan.danhhieu.index')->with('ok','Đã cập nhật danh hiệu.');
    }

    public function dhDelete(Request $r)
    {
        $r->validate([
            'MaDH' => 'required|integer|exists:bang_danhhieu,MaDH',
        ]);

        DanhHieu::destroy($r->MaDH);

        return redirect()->route('doan.danhhieu.index')->with('ok','Đã xóa danh hiệu.');
    }
}
