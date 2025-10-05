@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lịch sử thanh toán - {{ $customer->name }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã giao dịch</th>
                <th>Số tiền</th>
                <th>Ngày</th>
                <th>Phương thức</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->transaction_code }}</td>
                    <td>{{ number_format($payment->amount) }} đ</td>
                    <td>
                        {{ $booking->ngay_dat ? \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y') : '---' }}
                    </td>
                    <td>{{ $payment->created_at }}</td>
                    <td>{{ $payment->method }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Chưa có lịch sử thanh toán</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
