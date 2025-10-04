@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-warning">
        <i class="fas fa-edit me-2"></i> Sửa sân bóng
    </h2>

    <form action="{{ route('admin.san-bong.update', $san->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Mã sân -->
        <div class="mb-3">
            <label for="ma_san" class="form-label">Mã sân</label>
            <input type="text" name="ma_san" id="ma_san" class="form-control" 
                   value="{{ old('ma_san', $san->ma_san) }}" required maxlength="6">
            <div class="form-text">Ví dụ: F5HN01, F7SG02...</div>
            @error('ma_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Tên sân -->
        <div class="mb-3">
            <label for="ten_san" class="form-label">Tên sân</label>
            <input type="text" name="ten_san" id="ten_san" class="form-control" 
                   value="{{ old('ten_san', $san->ten_san) }}" required>
            @error('ten_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Loại sân -->
        <div class="mb-3">
            <label for="loai_san" class="form-label">Loại sân</label>
            <select name="loai_san" id="loai_san" class="form-select" required>
                <option value="">-- Chọn loại sân --</option>
                <option value="5 người" {{ old('loai_san', $san->loai_san) == '5 người' ? 'selected' : '' }}>5 người</option>
                <option value="7 người" {{ old('loai_san', $san->loai_san) == '7 người' ? 'selected' : '' }}>7 người</option>
                <option value="11 người" {{ old('loai_san', $san->loai_san) == '11 người' ? 'selected' : '' }}>11 người</option>
                <option value="Futsal" {{ old('loai_san', $san->loai_san) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
            </select>
            @error('loai_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Giá thuê -->
        <div class="mb-3">
            <label for="gia_thue" class="form-label">Giá thuê (đ/giờ)</label>
            <input type="number" name="gia_thue" id="gia_thue" class="form-control" 
                   value="{{ old('gia_thue', $san->gia_thue) }}" required min="1">
            @error('gia_thue') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Giờ hoạt động -->
        <div class="row mb-3">
            <div class="col">
                <label for="start_time" class="form-label">Giờ mở cửa</label>
                <input type="time" name="start_time" id="start_time" class="form-control" 
                       value="{{ old('start_time', $san->start_time) }}" required>
                @error('start_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col">
                <label for="end_time" class="form-label">Giờ đóng cửa</label>
                <input type="time" name="end_time" id="end_time" class="form-control" 
                       value="{{ old('end_time', $san->end_time) }}" required>
                @error('end_time') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select" required>
                <option value="available" {{ old('status', $san->status) == 'available' ? 'selected' : '' }}>Còn trống</option>
                <option value="booked" {{ old('status', $san->status) == 'booked' ? 'selected' : '' }}>Đã đặt</option>
            </select>
            @error('status') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save"></i> Cập nhật
        </button>
        <a href="{{ route('admin.san-bong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
