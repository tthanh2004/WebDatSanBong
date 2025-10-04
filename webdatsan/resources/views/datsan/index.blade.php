@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 fw-bold">Danh sách sân đã đặt</h3>

    <div class="row g-4">
        @forelse($datSan as $ds)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <!-- Hình ảnh sân -->
                    <img src="{{ asset('images/football_field.png') }}" class="card-img-top" alt="{{ $ds->ten_san ?? 'Sân bóng' }}">

                    <div class="card-body">
                        <!-- Tên sân -->
                        <h5 class="card-title fw-bold">{{ $ds->sanBong?->ten_san ?? $ds->ten_san ?? 'Sân bóng' }}</h5>

                        <!-- Người đặt -->
                        <p class="mb-1"><i class="fas fa-user text-primary me-2"></i> 
                            {{ $ds->ho_ten ?? $ds->user->name ?? 'N/A' }}
                        </p>

                        <!-- Thời gian -->
                        <p class="mb-1">
                            <i class="fas fa-clock text-success me-2"></i>
                            {{ $ds->gio_bat_dau }} - {{ $ds->gio_ket_thuc }}
                        </p>

                        <!-- Ngày -->
                        <p class="mb-1">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            {{ $ds->ngay_dat ?? 'Chưa có ngày' }}
                        </p>

                        <!-- Trạng thái -->
                        <p class="mb-1">
                            <i class="fas fa-info-circle me-2"></i>
                            @if($ds->trang_thai === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($ds->trang_thai === 'pending')
                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                            @elseif($ds->trang_thai === 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">Không xác định</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($ds->trang_thai === 'pending')
                        <div class="card-footer bg-white border-0 d-flex gap-2">
                            <!-- Thanh toán -->
                            <a href="{{ url('/thanh-toan') }}" 
                            class="btn btn-primary flex-fill d-flex justify-content-center align-items-center py-2 rounded-pill">
                                <i class="fas fa-credit-card me-2"></i> Thanh toán
                            </a>

                            <!-- Chỉnh sửa -->
                            <a href="{{ route('datsan.edit', $ds->id) }}" 
                            class="btn btn-outline-primary flex-fill d-flex justify-content-center align-items-center py-2 rounded-pill">
                                <i class="fas fa-edit me-2"></i> Chỉnh sửa
                            </a>

                            <!-- Hủy -->
                            <form action="{{ route('datsan.huy', $ds->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đặt sân này?')"
                                        class="btn btn-outline-danger w-100 d-flex justify-content-center align-items-center py-2 rounded-pill">
                                    <i class="fas fa-times me-2"></i> Hủy
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center">Bạn chưa đặt sân nào.</p>
        @endforelse
    </div>
</div>
@endsection
