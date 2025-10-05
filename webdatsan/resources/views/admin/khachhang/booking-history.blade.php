@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold text-primary mb-4">
        <i class="fas fa-history"></i> Lịch sử đặt sân - {{ $customer->name }}
    </h2>

    <div class="card shadow border-0 rounded-3">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Mã KH</th>
                        <th>Tên sân</th>
                        <th>Loại sân</th>
                        <th>Giờ hoạt động</th>
                        <th>Giá (VNĐ/giờ)</th>
                        <th>Ngày đặt</th>
                        <th>Giờ thuê</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="text-center">
                            <td class="fw-bold text-secondary">{{ $booking->ma_khach_hang }}</td>
                            <td>{{ $booking->ten_san }}</td>
                            <td><span class="badge bg-info">{{ $booking->loai_san }}</span></td>
                            <td>{{ $booking->sanBong->start_time ?? '' }} - {{ $booking->sanBong->end_time ?? '' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($booking->sanBong->gia_thue ?? 0) }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->ngay_dat)->format('d/m/Y') }}</td>
                            <td>{{ $booking->gio_bat_dau }} - {{ $booking->gio_ket_thuc }}</td>
                            <td>
                                @if($booking->trang_thai == 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @elseif($booking->trang_thai == 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($booking->trang_thai == 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $booking->trang_thai }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i> Chưa có lịch sử đặt sân
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
