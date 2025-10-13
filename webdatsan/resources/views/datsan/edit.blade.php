@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold">Chỉnh sửa đặt sân: {{ $datSan->ten_san }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Giờ hoạt động --}}
    <div class="alert alert-info">
        <strong>Giờ hoạt động:</strong> {{ $datSan->sanBong->start_time }} - {{ $datSan->sanBong->end_time }}
    </div>

    <form action="{{ route('datsan.update', $datSan->id) }}" method="POST" class="card p-4 shadow rounded">
        @csrf
        @method('PUT')

        {{-- Thông tin khách hàng --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Tên khách hàng</label>
            <input type="text" name="ho_ten" 
                   value="{{ old('ho_ten', $datSan->ho_ten ?? $user->name) }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Số điện thoại</label>
            <input type="text" name="so_dien_thoai"
                   value="{{ old('so_dien_thoai', $datSan->so_dien_thoai ?? $user->so_dien_thoai) }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $datSan->email ?? $user->email) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mã khách hàng</label>
            @if(Auth::user()->role === 'admin')
                <select name="ma_khach_hang" class="form-control" required>
                    <option value="">-- Chọn khách hàng --</option>
                    @foreach($customers as $cus)
                        <option value="{{ $cus->ma_khach_hang }}"
                            {{ old('ma_khach_hang', $datSan->ma_khach_hang) == $cus->ma_khach_hang ? 'selected' : '' }}>
                            {{ $cus->ma_khach_hang }} - {{ $cus->ten_khach_hang }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="text" name="ma_khach_hang" 
                    value="{{ old('ma_khach_hang', $datSan->ma_khach_hang) }}" 
                    class="form-control" readonly>
            @endif
        </div>

        {{-- Thông tin sân --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Mã sân</label>
            <input type="text" value="{{ $datSan->sanBong->ma_san }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Loại sân</label>
            <input type="text" value="{{ $datSan->loai_san }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Giá thuê (VNĐ/giờ)</label>
            <input type="text" value="{{ number_format($datSan->sanBong->gia_thue) }}" class="form-control" readonly>
        </div>

        {{-- Thời gian thuê --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giờ bắt đầu</label>
                <select name="gio_bat_dau" class="form-control" required>
                    @php
                        $startHour = (int) substr($datSan->sanBong->start_time, 0, 2);
                        $endHour   = (int) substr($datSan->sanBong->end_time, 0, 2);
                    @endphp
                    @for ($i = $startHour; $i <= $endHour; $i++)
                        <option value="{{ sprintf('%02d:00', $i) }}" 
                            {{ $datSan->gio_bat_dau == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                            {{ sprintf('%02d:00', $i) }}
                        </option>
                        <option value="{{ sprintf('%02d:30', $i) }}" 
                            {{ $datSan->gio_bat_dau == sprintf('%02d:30', $i) ? 'selected' : '' }}>
                            {{ sprintf('%02d:30', $i) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giờ kết thúc</label>
                <select name="gio_ket_thuc" class="form-control" required>
                    @for ($i = $startHour; $i <= $endHour; $i++)
                        <option value="{{ sprintf('%02d:00', $i) }}" 
                            {{ $datSan->gio_ket_thuc == sprintf('%02d:00', $i) ? 'selected' : '' }}>
                            {{ sprintf('%02d:00', $i) }}
                        </option>
                        <option value="{{ sprintf('%02d:30', $i) }}" 
                            {{ $datSan->gio_ket_thuc == sprintf('%02d:30', $i) ? 'selected' : '' }}>
                            {{ sprintf('%02d:30', $i) }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Ngày đặt --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Ngày đặt</label>
            <input type="date" name="ngay_dat" 
                   value="{{ old('ngay_dat', $datSan->ngay_dat) }}" 
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-save me-2"></i> Cập nhật
        </button>
        <a href="{{ route('datsan.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </form>
</div>
@endsection
