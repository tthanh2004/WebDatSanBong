@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">Danh sách sân bóng</h2>

    @if($sanBong->isEmpty())
        <!-- Nút thêm sân cho admin (nằm riêng 1 div) -->
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="mb-4 text-end">
                <a href="{{ route('admin.san-bong.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm sân mới
                </a>
            </div>
        @endif

        <!-- Thông báo căn giữa -->
        <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
            <div class="alert alert-info text-center shadow-sm px-4 py-3">
                <i class="fas fa-info-circle me-2"></i> 
                <span class="fw-bold">Chưa có sân nào trong hệ thống.</span>
            </div>
        </div>
    @else
        <!-- Nếu có sân -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($sanBong as $san)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <img src="{{ asset('images/football_field.png') }}" class="card-img-top rounded-top" alt="Sân bóng">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $san->ten_san }}</h5>
                            <p class="card-text text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $san->loai_san }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-clock me-1 text-primary"></i>
                                {{ $san->start_time }} - {{ $san->end_time }}
                            </p>
                            <p class="text-success fw-bold mb-2">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                {{ number_format($san->gia_thue, 0, ',', '.') }} VND
                            </p>
                            <p class="text-warning mb-3">
                                <i class="fas fa-star"></i> 0 lượt đánh giá
                            </p>
                            <a href="{{ route('datsan.create', $san->_id) }}" class="btn btn-primary w-100">
                                <i class="fas fa-futbol me-1"></i> Đặt ngay
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Nút thêm sân chỉ cho admin -->
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="mt-4 text-end">
                <a href="{{ route('admin.san-bong.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm sân mới
                </a>
            </div>
        @endif
    @endif
</div>
@endsection
