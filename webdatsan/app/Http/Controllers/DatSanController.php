<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatSan;
use App\Models\SanBong;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDatSanRequest;
use App\Models\Customer;

class DatSanController extends Controller
{
    public function create($sanId)
    {
        $san = SanBong::findOrFail($sanId);
        $user = auth()->user();

        $customers = [];
        if ($user->role === 'admin') {
            $customers = Customer::all();
        } else {
            $customers = Customer::where('email', $user->email)->get();
        }

        return view('datsan.create', compact('san', 'user', 'customers'));
    }

    public function store(StoreDatSanRequest $request, $sanId)
    {
        $san = SanBong::findOrFail($sanId);
        $user = auth()->user();

        if ($request->gio_bat_dau < $san->start_time || $request->gio_ket_thuc > $san->end_time) {
            return back()->withErrors(['error' => 'Giờ thuê phải nằm trong giờ hoạt động của sân.'])->withInput();
        }

        $maKhachHang = null;
        if ($user->role === 'admin') {
            $maKhachHang = $request->ma_khach_hang ?: null;
        } else {
            $customer = Customer::where('email', $user->email)->first();
            $maKhachHang = $customer ? $customer->ma_khach_hang : null;
        }

        DatSan::create([
            'user_id'      => $user->id,
            'ho_ten'       => $request->ho_ten,
            'so_dien_thoai'=> $request->so_dien_thoai,
            'email'        => $request->email,
            'ma_khach_hang'=> $maKhachHang,
            'san_bong_id'  => (string) $san->_id,
            'ten_san'      => $san->ten_san,
            'loai_san'     => $san->loai_san,
            'ngay_dat'     => $request->ngay_dat,
            'gio_bat_dau'  => $request->gio_bat_dau,
            'gio_ket_thuc' => $request->gio_ket_thuc,
            'trang_thai'   => 'pending',
        ]);

        return redirect()->route('datsan.index')->with('success', 'Đặt sân thành công!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $query = DatSan::with('sanBong');
        } else {
            $query = DatSan::where('user_id', $user->id)->with('sanBong');
        }

        // Lọc theo ngày
        if ($request->filled('ngay_dat')) {
            $query->whereDate('ngay_dat', $request->ngay_dat);
        }

        // Lọc theo tên sân (theo id)
        if ($request->filled('san_bong_id')) {
            $query->where('san_bong_id', $request->san_bong_id);
        }

        $datSan = $query->paginate(6)->withQueryString();

        // Lấy danh sách sân từ CSDL
        $sanBongs = \App\Models\SanBong::all();

        return view('datsan.index', compact('datSan', 'sanBongs'));
    }



    public function huyDatSan($id)
    {
        $datSan = DatSan::findOrFail($id);

        // Chỉ cho người đặt hủy (hoặc admin nếu muốn)
        if (auth()->id() !== $datSan->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn này.');
        }

        if ($datSan->trang_thai === 'success') {
            return redirect()->back()->with('error', 'Đơn đã thanh toán, không thể hủy.');
        }

        $datSan->delete();

        return redirect()->back()->with('success', 'Bạn đã hủy đặt sân thành công.');
    }

    public function edit($id)
    {
        $datSan = DatSan::findOrFail($id);
        $user = auth()->user();

        $customers = [];
        if ($user->role === 'admin') {
            $customers = Customer::all();
        } else {
            $customers = Customer::where('email', $user->email)->get();
        }

        return view('datsan.edit', compact('datSan', 'user', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $datSan = DatSan::findOrFail($id);
        $user = auth()->user();

        if ($user->id !== $datSan->user_id && $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đặt sân này.');
        }

        $request->validate([
            'ho_ten'       => 'required|string|max:255',
            'ngay_dat'     => 'required|date',
            'gio_bat_dau'  => 'required',
            'gio_ket_thuc' => 'required|after:gio_bat_dau',
        ]);

        $maKhachHang = $datSan->ma_khach_hang;
        if ($user->role === 'admin') {
            $maKhachHang = $request->ma_khach_hang ?: null;
        }

        $datSan->update([
            'ho_ten'        => $request->ho_ten,
            'ngay_dat'      => $request->ngay_dat,
            'gio_bat_dau'   => $request->gio_bat_dau,
            'gio_ket_thuc'  => $request->gio_ket_thuc,
            'ma_khach_hang' => $maKhachHang,
        ]);

        return redirect()->route('datsan.index')->with('success', 'Cập nhật thông tin đặt sân thành công.');
    }
}
