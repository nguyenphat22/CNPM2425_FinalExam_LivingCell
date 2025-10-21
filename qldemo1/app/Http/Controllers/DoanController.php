Fch danh hiệu
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
                'TenDH'