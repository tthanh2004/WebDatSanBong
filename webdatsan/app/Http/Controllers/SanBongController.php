<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanBong;

class SanBongController extends Controller
{
    // Hiển thị danh sách sân cho KHÁCH HÀNG
    public function indexClient()
    {
        $sanBong = \App\Models\SanBong::whereIn('status', ['available', 'booked'])
            ->paginate(10);

        return view('sanCustomers.index', compact('sanBong'));
    }

}
