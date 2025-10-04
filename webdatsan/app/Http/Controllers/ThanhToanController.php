<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThanhToan;   // ✅ dùng model này
use App\Models\SanBong;
use Illuminate\Support\Facades\Auth;

class ThanhToanController extends Controller
{
    // Hiển thị form thanh toán
    public function create($sanBongId)
    {
        $san = SanBong::findOrFail($sanBongId);
        return view('thanh_toan.create', compact('san'));
    }

    // Xử lý lưu thanh toán
    public function store(Request $request, $sanBongId)
    {
        $san = SanBong::findOrFail($sanBongId);

        ThanhToan::create([
            'user_id'     => Auth::id(),
            'san_bong_id' => $san->_id,
            'so_tien'     => $san->gia_thue,
            'trang_thai'  => 'pending',
            'phuong_thuc' => $request->phuong_thuc,
        ]);

        return redirect()->route('thanh-toan.index')
                         ->with('success', 'Yêu cầu thanh toán đã được tạo!');
    }

    // Xem danh sách giao dịch
    public function index()
    {
        $giaoDich = ThanhToan::with(['user','sanBong'])->get();

        return view('thanh_toan.index', compact('giaoDich'));
    }

    // Hủy giao dịch
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
}
