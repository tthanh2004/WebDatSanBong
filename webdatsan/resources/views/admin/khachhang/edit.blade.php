@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa khách hàng</h2>

    <form action="{{ route('admin.khach-hang.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên khách hàng</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
