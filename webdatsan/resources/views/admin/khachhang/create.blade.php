@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-success">
        <i class="fas fa-user-plus me-2"></i> Thêm khách hàng
    </h2>

    <form method="POST" action="{{ route('admin.khach-hang.store') }}" class="shadow p-4 bg-white rounded-3">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Mã khách hàng</label>
            <input type="text" name="ma_khach_hang" class="form-control @error('ma_khach_hang') is-invalid @enderror"
                   value="{{ old('ma_khach_hang') }}" placeholder="Ví dụ: KH123456">
            @error('ma_khach_hang') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tên khách hàng</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Số điện thoại</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Địa chỉ</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                      rows="3">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Lưu khách hàng
            </button>
        </div>
    </form>
</div>
@endsection
