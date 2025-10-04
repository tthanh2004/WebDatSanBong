@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold">Chỉnh sửa đặt sân: {{ $datSan->ten_san }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    {{-- Giờ hoạt động --}}
    <div class="alert alert-info">
        <strong>Giờ hoạt động:</strong> {{ $datSan->start_time }} - {{ $datSan->end_time }}
    </div>

    <form action="{{ route('datsan.update', $datSan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Họ tên (cho phép sửa) -->
        <div class="mb-3">
            <label class="fw-bold">Họ tên</label>
            <input type="text" name="ho_ten" class="form-control" 
                value="{{ old('ho_ten', $datSan->ho_ten ?? $user->name) }}" required>
        </div>

        <!-- Số điện thoại -->
        <div class="mb-3">
            <label class="fw-bold">Số điện thoại</label>
            <input type="text" class="form-control" 
                value="{{ $datSan->so_dien_thoai ?? $user->so_dien_thoai }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="fw-bold">Email</label>
            <input type="email" class="form-control" 
                value="{{ $datSan->email ?? $user->email }}" required>
        </div>

        <!-- Loại sân -->
        <div class="mb-3">
            <label class="fw-bold">Loại sân</label>
            <input type="text" class="form-control" 
                value="{{ $datSan->loai_san }}" readonly>
        </div>

        <!-- Giờ bắt đầu -->
        <div class="mb-3">
            <label class="fw-bold">Giờ bắt đầu</label>
            <select name="gio_bat_dau" class="form-control" required>
                @for ($i = 0; $i < 24; $i++)
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

        <!-- Giờ kết thúc -->
        <div class="mb-3">
            <label class="fw-bold">Giờ kết thúc</label>
            <select name="gio_ket_thuc" class="form-control" required>
                @for ($i = 0; $i < 24; $i++)
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

        <!-- Phương thức thanh toán -->
        <div class="mb-3">
            <label class="fw-bold">Phương thức thanh toán</label>
            <select name="payment_method" class="form-control" required>
                <option value="cash" {{ $datSan->payment_method == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                <option value="bank" {{ $datSan->payment_method == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
                <option value="momo" {{ $datSan->payment_method == 'momo' ? 'selected' : '' }}>Momo</option>
                <option value="zalopay" {{ $datSan->payment_method == 'zalopay' ? 'selected' : '' }}>ZaloPay</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> Cập nhật
        </button>
        <a href="{{ route('sanda-dat.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
