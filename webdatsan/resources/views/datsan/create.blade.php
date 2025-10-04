@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Đặt sân: {{ $san->ten_san }} ({{ $san->loai_san }})</h2>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    {{-- Hiển thị giờ hoạt động của sân --}}
    <div class="alert alert-info">
        <strong>Giờ hoạt động:</strong> {{ $san->start_time }} - {{ $san->end_time }}
    </div>

    <form action="{{ route('datsan.store', $san->_id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Họ tên</label>
            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" class="form-control" value="{{ $user->so_dien_thoai ?? '' }}" readonly>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
        </div>

        <div class="mb-3">
            <label>Giờ bắt đầu</label>
            <select name="gio_bat_dau" class="form-control" required>
                @for ($i = 0; $i < 24; $i++)
                    <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                    <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label>Giờ kết thúc</label>
            <select name="gio_ket_thuc" class="form-control" required>
                @for ($i = 0; $i < 24; $i++)
                    <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                    <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Đặt sân</button>
    </form>
</div>
@endsection
