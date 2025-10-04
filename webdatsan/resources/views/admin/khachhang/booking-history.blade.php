@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lịch sử đặt sân - {{ $customer->name }}</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sân</th>
                <th>Ngày</th>
                <th>Khung giờ</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->field_name }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>{{ $booking->time_slot }}</td>
                    <td>{{ $booking->status }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Chưa có lịch sử đặt sân</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
