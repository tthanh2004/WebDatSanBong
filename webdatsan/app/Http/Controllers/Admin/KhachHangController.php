<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    /**
     * Hiển thị danh sách khách hàng
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('phone', 'like', '%' . $request->keyword . '%');
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.khachhang.index', compact('customers'));
    }

    /**
     * Form thêm khách hàng mới
     */
    public function create()
    {
        return view('admin.khachhang.create');
    }

    /**
     * Lưu khách hàng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
            'role'     => 'user', // mặc định role là khách hàng
        ]);

        return redirect()->route('admin.khachhang.index')
                         ->with('success', 'Thêm khách hàng thành công!');
    }

    /**
     * Form sửa khách hàng
     */
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.khachhang.edit', compact('customer'));
    }

    /**
     * Cập nhật khách hàng
     */
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $customer->name  = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        if ($request->filled('password')) {
            $customer->password = bcrypt($request->password);
        }

        $customer->save();

        return redirect()->route('admin.khachhang.index')
                         ->with('success', 'Cập nhật khách hàng thành công!');
    }

    /**
     * Xóa khách hàng
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.khachhang.index')
                         ->with('success', 'Xóa khách hàng thành công!');
    }
}
