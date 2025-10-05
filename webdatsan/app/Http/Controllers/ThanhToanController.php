<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThanhToanRequest;
use App\Models\ThanhToan;
use App\Models\SanBong;
use App\Models\Customer;
use App\Models\DatSan;
use Illuminate\Support\Facades\Auth;

class ThanhToanController extends Controller
{
    // Hiển thị form
    public function create($sanBongId)
    {
        $san = SanBong::findOrFail($sanBongId);
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        return view('thanh_toan.create', compact('san', 'customer'));
    }

    // Lưu giao dịch
    public function store(StoreThanhToanRequest $request, $sanBongId)
    {
        $san = SanBong::findOrFail($sanBongId);

        // Kiểm tra số tiền phải khớp
        if ((int)$request->so_tien !== (int)$san->gia_thue) {
            return back()->withErrors(['so_tien' => 'Số tiền phải bằng giá thuê của sân.'])->withInput();
        }

        ThanhToan::create([
            'user_id'        => Auth::id(),
            'san_bong_id'    => $san->_id,
            'so_tien'        => $request->so_tien,
            'trang_thai'     => 'pending',
            'phuong_thuc'    => $request->phuong_thuc,
            'ngay_thanh_toan'=> now(),
        ]);

        return redirect()->route('thanh-toan.index')->with('success', 'Thanh toán thành công!');
    }

    public function index()
    {
        $giaoDich = ThanhToan::with(['user','sanBong'])->get();
        return view('thanh_toan.index', compact('giaoDich'));
    }

    public function cancel($id)
    {
        $giaoDich = ThanhToan::where('_id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        if ($giaoDich->trang_thai === 'pending') {
            $giaoDich->update(['trang_thai' => 'cancelled']);
            return back()->with('success', 'Giao dịch đã được hủy.');
        }

        return back()->with('error', 'Không thể hủy giao dịch này.');
    }

    // Admin duyệt thanh toán
    public function approve($id)
    {
        $giaoDich = ThanhToan::findOrFail($id);

        if ($giaoDich->trang_thai !== 'pending') {
            return back()->with('error', 'Giao dịch đã được xử lý trước đó.');
        }

        $giaoDich->update([
            'trang_thai' => 'success',
            'ngay_thanh_toan' => now(),
        ]);

        return back()->with('success', 'Thanh toán đã được xác nhận thành công.');
    }

    // Admin từ chối thanh toán
    public function reject($id)
    {
        $giaoDich = ThanhToan::findOrFail($id);

        if ($giaoDich->trang_thai !== 'pending') {
            return back()->with('error', 'Giao dịch đã được xử lý trước đó.');
        }

        $giaoDich->update([
            'trang_thai' => 'cancelled',
        ]);

        return back()->with('success', 'Thanh toán đã bị từ chối.');
    }

    // Trang admin quản lý thanh toán
    public function adminIndex()
    {
        $giaoDich = ThanhToan::with(['user', 'sanBong'])->latest()->get();
        return view('admin.thanh_toan.index', compact('giaoDich'));
    }


}
