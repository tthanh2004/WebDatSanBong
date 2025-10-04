<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatSan;
use App\Models\SanBong;
use Illuminate\Support\Facades\Auth;

class DatSanController extends Controller
{
    public function create($sanId)
    {
        $san = SanBong::findOrFail($sanId);
        $user = Auth::user();

        return view('datsan.create', compact('san', 'user'));
    }

    public function store(Request $request, $sanId)
    {
        $san = SanBong::findOrFail($sanId);
        $user = Auth::user();

        $request->validate([
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
        ]);

        if ($request->gio_bat_dau < $san->start_time || $request->gio_ket_thuc > $san->end_time) {
            return back()->withErrors(['error' => 'Giờ thuê phải nằm trong giờ hoạt động của sân.']);
        }

        DatSan::create([
            'user_id'       => $user->_id,
            'ho_ten'        => $user->name,
            'so_dien_thoai' => $user->so_dien_thoai ?? '',
            'email'         => $user->email,
            'ma_khach_hang' => $user->ma_khach_hang ?? '',
            'san_bong_id'   => $san->_id,
            'ten_san'       => $san->ten_san,
            'loai_san'      => $san->loai_san,
            'gio_bat_dau'   => $request->gio_bat_dau,
            'gio_ket_thuc'  => $request->gio_ket_thuc,
            'trang_thai'    => 'pending',
        ]);

        return redirect()->route('sanda-dat.index')
                         ->with('success', 'Bạn đã đặt sân thành công, vui lòng thanh toán.');
    }

    public function index()
    {
        $datSan = DatSan::where('user_id', Auth::id())->with('sanBong')->get();
        return view('datsan.index', compact('datSan'));
    }

    public function huyDatSan($id)
    {
        $datSan = DatSan::findOrFail($id);

        // Chỉ cho người đặt hủy (hoặc admin nếu muốn)
        if (auth()->id() !== $datSan->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn này.');
        }

        // Không cho hủy nếu đã thanh toán
        if ($datSan->trang_thai === 'paid') {
            return redirect()->back()->with('error', 'Đơn đã thanh toán, không thể hủy.');
        }

        $datSan->trang_thai = 'cancelled';
        $datSan->save();

        return redirect()->back()->with('success', 'Bạn đã hủy đặt sân thành công.');
    }
    public function edit($id)
    {
        $datSan = DatSan::findOrFail($id);

        if (auth()->id() !== $datSan->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đặt sân này.');
        }

        return view('datsan.edit', compact('datSan'));
    }

    public function update(Request $request, $id)
    {
        $datSan = DatSan::findOrFail($id);

        if (auth()->id() !== $datSan->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đặt sân này.');
        }

        $request->validate([
            'ho_ten'       => 'required|string|max:255',
            'ngay_dat'     => 'required|date',
            'gio_bat_dau'  => 'required',
            'gio_ket_thuc' => 'required|after:gio_bat_dau',
        ]);

        $datSan->update([
            'ho_ten'       => $request->ho_ten,
            'ngay_dat'     => $request->ngay_dat,
            'gio_bat_dau'  => $request->gio_bat_dau,
            'gio_ket_thuc' => $request->gio_ket_thuc,
        ]);

        return redirect()->route('sanda-dat.index')->with('success', 'Cập nhật thông tin đặt sân thành công.');
    }



}
