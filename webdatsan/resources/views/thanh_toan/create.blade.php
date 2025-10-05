@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="fw-bold text-primary mb-4">
        <i class="fas fa-credit-card"></i> Thanh toán sân: {{ $san->ten_san }}
    </h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('thanh-toan.store', $san->_id) }}" method="POST" class="card p-4 shadow">
        @csrf

        {{-- Nếu KHÔNG phải admin thì hiển thị Mã khách hàng --}}
        @if(auth()->user()->role !== 'admin')
            <div class="mb-3">
                <label class="form-label fw-bold">Mã khách hàng</label>
                <input type="text" name="ma_khach_hang" 
                       value="{{ old('ma_khach_hang', $customer->ma_khach_hang ?? '') }}" 
                       class="form-control" required>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-bold">Mã sân</label>
            <input type="text" name="ma_san" value="{{ old('ma_san', $san->ma_san) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Số tiền thanh toán (VNĐ)</label>
            <input type="number" name="so_tien" value="{{ old('so_tien', $san->gia_thue) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Phương thức thanh toán</label>
            <select name="phuong_thuc" class="form-control" required>
                <option value="">-- Chọn phương thức --</option>
                <option value="Tiền mặt" {{ old('phuong_thuc') == 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                <option value="Chuyển khoản" {{ old('phuong_thuc') == 'Chuyển khoản' ? 'selected' : '' }}>Chuyển khoản</option>
                <option value="Ví điện tử" {{ old('phuong_thuc') == 'Ví điện tử' ? 'selected' : '' }}>Ví điện tử</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Xác nhận thanh toán
        </button>
    </form>
</div>
@endsection
