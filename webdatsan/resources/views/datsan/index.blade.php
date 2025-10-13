@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 fw-bold">Danh sách sân đã đặt</h3>

    <!-- Bộ lọc tìm kiếm -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('datsan.index') }}">
                <div class="row g-3 align-items-end">
                    <!-- Ngày đặt -->
                    <div class="col-md-4">
                        <label for="ngay_dat" class="form-label fw-bold">Ngày đặt</label>
                        <input type="date" id="ngay_dat" name="ngay_dat" value="{{ request('ngay_dat') }}" class="form-control">
                    </div>

                    <!-- Tên sân -->
                    <div class="col-md-4">
                        <label for="san_bong_id" class="form-label fw-bold">Sân bóng</label>
                        <select id="san_bong_id" name="san_bong_id" class="form-select">
                            <option value="">-- Chọn sân --</option>
                            @foreach($sanBongs as $san)
                                <option value="{{ $san->id }}" {{ request('san_bong_id') == $san->id ? 'selected' : '' }}>
                                    {{ $san->ten_san }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nút hành động -->
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('datsan.index') }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-sync-alt me-2"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="row g-4">
        @forelse($datSan as $ds)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <!-- Hình ảnh sân -->
                    <img src="{{ asset('images/football_field.png') }}" class="card-img-top" alt="{{ $ds->ten_san ?? 'Sân bóng' }}">

                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $ds->sanBong?->ten_san ?? $ds->ten_san ?? 'Sân bóng' }}</h5>

                        <p class="mb-1"><i class="fas fa-user text-primary me-2"></i> 
                            {{ $ds->ho_ten ?? $ds->user->name ?? 'N/A' }}
                        </p>

                        <p class="mb-1"><i class="fas fa-clock text-success me-2"></i>
                            {{ $ds->gio_bat_dau }} - {{ $ds->gio_ket_thuc }}
                        </p>

                        <p class="mb-1"><i class="fas fa-calendar-alt text-info me-2"></i>
                            {{ $ds->ngay_dat ? \Carbon\Carbon::parse($ds->ngay_dat)->format('d/m/Y') : 'Chưa có ngày' }}
                        </p>

                        <p class="mb-1"><i class="fas fa-info-circle me-2"></i>
                            @if($ds->trang_thai === 'success')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($ds->trang_thai === 'pending')
                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($ds->trang_thai === 'pending')
                        <div class="card-footer bg-white border-0 d-flex gap-2">
                            <a href="{{ route('thanh-toan.create', $ds->san_bong_id) }}" class="btn btn-primary flex-fill">
                                <i class="fas fa-credit-card me-2"></i> Thanh toán
                            </a>
                            <a href="{{ route('datsan.edit', $ds->id) }}" class="btn btn-outline-primary flex-fill">
                                <i class="fas fa-edit me-2"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('datsan.huy', $ds->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn hủy đặt sân này?')" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-times me-2"></i> Hủy
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center">Không tìm thấy sân nào phù hợp.</p>
        @endforelse
    </div>

    <!-- Phân trang -->
    <div class="mt-4">
        {{ $datSan->links() }}
    </div>
</div>
@endsection
