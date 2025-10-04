@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">Thanh toán sân: {{ $san->ten_san }}</h3>

    <form method="POST" action="{{ route('thanh-toan.store', $san->_id) }}">
        @csrf
        <p><strong>Giá thuê:</strong> {{ number_format($san->gia_thue, 0, ',', '.') }} VND</p>

        <div class="mb-3">
            <label class="form-label">Phương thức thanh toán</label>
            <select class="form-select" name="phuong_thuc" required>
                <option value="momo">MoMo</option>
                <option value="vnpay">VNPay</option>
                <option value="cash">Tiền mặt</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
    </form>
</div>
@endsection
