<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanBong;

class SanBongAdminController extends Controller
{
    // Hiển thị danh sách sân bóng
    public function index()
    {
        $sanBong = SanBong::paginate(10);
        return view('admin.sanbong.index', compact('sanBong'));
    }

    // Form thêm sân
    public function create()
    {
        return view('admin.sanbong.create');
    }

    // Lưu sân mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_san' => [
                'required',
                'string',
                'size:6',
                'regex:/^(F5|F7|F11|FS)([A-Z0-9]{2})([0-9]{2})$/',
                'unique:san_bong,ma_san',
            ],
            'ten_san'   => 'required|string|max:255',
            'loai_san'  => 'required|in:5 người,7 người,11 người,Futsal',
            'gia_thue'  => 'required|numeric|min:1',
            'start_time'=> 'required|date_format:H:i',
            'end_time'  => 'required|date_format:H:i|different:start_time',
        ], [
            'ma_san.regex' => 'Mã sân phải đúng định dạng (VD: F5HN01, F7SG02...).',
        ]);

        // Kiểm tra logic giờ mở - giờ đóng
        $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);

        // Cho phép end < start (qua đêm), chỉ chặn trường hợp trùng
        if ($start->equalTo($end)) {
            return back()->withErrors(['end_time' => 'Giờ kết thúc không được trùng giờ bắt đầu'])->withInput();
        }

        // Lưu DB
        \App\Models\SanBong::create($validated + ['status' => 'available']);

        return redirect()->route('admin.san-bong.index')->with('success', 'Thêm sân thành công');
    }


    // Form sửa
    public function edit($id)
    {
        $san = SanBong::findOrFail($id);
        return view('admin.sanbong.edit', compact('san'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $sanBong = \App\Models\SanBong::findOrFail($id);

        $validated = $request->validate([
            'ma_san' => [
                'required',
                'string',
                'size:6',
                'regex:/^(F5|F7|F11|FS)([A-Z0-9]{2})([0-9]{2})$/',
                Rule::unique('san_bong', 'ma_san')->ignore($sanBong->id),
            ],
            'ten_san'   => 'required|string|max:255',
            'loai_san'  => 'required|in:5 người,7 người,11 người,Futsal',
            'gia_thue'  => 'required|numeric|min:1',
            'start_time'=> 'required|date_format:H:i',
            'end_time'  => 'required|date_format:H:i|different:start_time',
            'status'    => 'required|in:available,booked',
        ], [
            'ma_san.regex' => 'Mã sân phải đúng định dạng (VD: F5HN01, F7SG02...).',
        ]);

        // Kiểm tra logic giờ mở - giờ đóng
        $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($start->equalTo($end)) {
            return back()->withErrors(['end_time' => 'Giờ kết thúc không được trùng giờ bắt đầu'])->withInput();
        }

        $sanBong->update($validated);

        return redirect()->route('admin.sanbong.index')->with('success', 'Cập nhật sân thành công');
    }

    // Xóa
    public function destroy($id)
    {
        $sanBong = SanBong::findOrFail($id);
        $sanBong->delete();

        return redirect()
            ->route('admin.san-bong.index')
            ->with('success', 'Xóa sân bóng thành công');
    }

    // Chuyển trạng thái (kích hoạt/tạm dừng)
    public function toggleStatus($id)
    {
        $sanBong = \App\Models\SanBong::findOrFail($id);

        if ($sanBong->status === 'inactive') {
            $sanBong->status = 'available'; // Kích hoạt lại
        } else {
            $sanBong->status = 'inactive'; // Tạm dừng
        }

        $sanBong->save();

        return redirect()
            ->route('admin.san-bong.index')
            ->with('success', 'Cập nhật trạng thái sân thành công');
    }

}
