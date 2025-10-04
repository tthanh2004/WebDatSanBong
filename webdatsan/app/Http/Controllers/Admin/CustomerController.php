<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(8);
        return view('admin.khachhang.index', compact('customers'));
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.khachhang.edit', compact('customer'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.khach-hang.index')->with('success', 'Cập nhật khách hàng thành công');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.khach-hang.index')->with('success', 'Xóa khách hàng thành công');
    }

    /** Lịch sử đặt sân */
    public function bookingHistory(string $id)
    {
        $customer = Customer::findOrFail($id);
        $bookings = $customer->bookings ?? []; // Quan hệ cần định nghĩa trong Model
        return view('admin.khachhang.booking-history', compact('customer', 'bookings'));
    }

    /** Lịch sử thanh toán */
    public function paymentHistory(string $id)
    {
        $customer = Customer::findOrFail($id);
        $payments = $customer->payments ?? []; // Quan hệ cần định nghĩa trong Model
        return view('admin.khachhang.payment-history', compact('customer', 'payments'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'role'     => 'user',
        'password' => Hash::make('123456'),
        ]);

        Customer::create([
            'ma_khach_hang' => $request->ma_khach_hang,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.khach-hang.index')
                        ->with('success', 'Thêm khách hàng thành công!');
    }


    public function create()
    {
        return view('admin.khachhang.create');
    }

}
