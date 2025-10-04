<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
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
            'ma_san'     => ['required', 'string', 'size:6', 'regex:/^SB\d{4}$/', 'unique:san_bong,ma_san'],
            'ten_san'    => ['required', 'string', 'max:50'],
            'loai_san'   => ['required', 'in:5,7,11'],
            'gia_thue'   => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'different:start_time'],
        ]);

        // Kiểm tra giờ hoạt động
        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($start->equalTo($end)) {
            return back()->withErrors(['end_time' => 'Giờ kết thúc không được trùng giờ bắt đầu'])->withInput();
        }

        // Lưu DB
        SanBong::create($validated + ['status' => 'available']);

        return redirect()->route('admin.sanbong.index')->with('success', 'Thêm sân thành công');
    }

    // Form sửa
    public function edit($id)
    {
        $san = SanBong::findOrFail($id);
        return view('admin.sanbong.edit', compact('san'));
    }

    // Cập nhật sân
    public function update(Request $request, $id)
    {
        $sanBong = SanBong::findOrFail($id);

        $validated = $request->validate([
            'ma_san'     => [
                'required',
                'string',
                'size:6',
                'regex:/^SB\d{4}$/',
                Rule::unique('san_bong', 'ma_san')->ignore($sanBong->id, '_id'),
            ],
            'ten_san'    => ['required', 'string', 'max:50'],
            'loai_san'   => ['required', 'in:5,7,11'],
            'gia_thue'   => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'different:start_time'],
            'status'     => ['required', 'in:available,maintenance,unavailable'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = Carbon::createFromFormat('H:i', $validated['end_time']);

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

        return redirect()->route('admin.sanbong.index')->with('success', 'Xóa sân bóng thành công');
    }

    // Chuyển trạng thái (active / inactive)
    public function toggleStatus($id)
    {
        $sanBong = SanBong::findOrFail($id);

        $sanBong->status = $sanBong->status === 'inactive' ? 'available' : 'inactive';
        $sanBong->save();

        return redirect()->route('admin.sanbong.index')->with('success', 'Cập nhật trạng thái sân thành công');
    }
}
