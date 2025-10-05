@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <h2 class="fw-bold mb-4 text-primary">Đặt sân bóng</h2>

    <form action="{{ route('datsan.store', $san->_id) }}" method="POST" class="card p-4 shadow rounded">
        @csrf

        {{-- Thông tin khách hàng --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Tên khách hàng</label>
            <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->name ?? '') }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Số điện thoại</label>
            <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai ?? '') }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email (không bắt buộc)</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mã khách hàng</label>
            <input type="text" name="ma_khach_hang" 
                value="{{ old('ma_khach_hang', $customer->ma_khach_hang ?? '') }}" 
                class="form-control" readonly>
        </div>

        {{-- Thông tin sân --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Mã sân</label>
            <input type="text" value="{{ $san->ma_san }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Loại sân</label>
            <input type="text" value="{{ $san->loai_san }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Giá thuê (VNĐ/giờ)</label>
            <input type="text" value="{{ number_format($san->gia_thue) }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Giờ hoạt động</label>
            <input type="text" value="{{ $san->start_time }} - {{ $san->end_time }}" class="form-control" readonly>
        </div>

        {{-- Thời gian thuê --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giờ bắt đầu</label>
                <select name="gio_bat_dau" class="form-control" required>
                    @php
                        $startHour = (int) substr($san->start_time, 0, 2); // ví dụ: "06:00" => 6
                        $endHour   = (int) substr($san->end_time, 0, 2);   // ví dụ: "22:00" => 22
                    @endphp

                    @for ($i = $startHour; $i <= $endHour; $i++)
                        <option value="{{ sprintf('%02d:00', $i) }}">
                            {{ sprintf('%02d:00', $i) }}
                        </option>
                        <option value="{{ sprintf('%02d:30', $i) }}">
                            {{ sprintf('%02d:30', $i) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giờ kết thúc</label>
                <select name="gio_ket_thuc" class="form-control" required>
                    @php
                        $startHour = (int) substr($san->start_time, 0, 2);
                        $endHour   = (int) substr($san->end_time, 0, 2);
                    @endphp

                    @for ($i = $startHour; $i <= $endHour; $i++)
                        <option value="{{ sprintf('%02d:00', $i) }}">
                            {{ sprintf('%02d:00', $i) }}
                        </option>
                        <option value="{{ sprintf('%02d:30', $i) }}">
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
                value="{{ old('ngay_dat') }}" 
                class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-check-circle"></i> Xác nhận đặt sân
        </button>
    </form>
</div>
@endsection

