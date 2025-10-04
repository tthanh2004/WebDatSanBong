@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-success">
        <i class="fas fa-plus-circle me-2"></i> Thêm sân bóng
    </h2>

    <form action="{{ route('admin.san-bong.store') }}" method="POST">
        @csrf

        <!-- Mã sân -->
        <div class="mb-3">
            <label for="ma_san" class="form-label">Mã sân</label>
            <input type="text" name="ma_san" id="ma_san" class="form-control" 
                   value="{{ old('ma_san') }}" required maxlength="6">
            <div class="form-text">Ví dụ: F5HN01, F7SG02...</div>
            @error('ma_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Tên sân -->
        <div class="mb-3">
            <label for="ten_san" class="form-label">Tên sân</label>
            <input type="text" name="ten_san" id="ten_san" class="form-control" 
                   value="{{ old('ten_san') }}" required>
            @error('ten_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Loại sân -->
        <div class="mb-3">
            <label for="loai_san" class="form-label">Loại sân</label>
            <select name="loai_san" id="loai_san" class="form-select" required>
                <option value="">-- Chọn loại sân --</option>
                <option value="5 người" {{ old('loai_san') == '5 người' ? 'selected' : '' }}>5 người</option>
                <option value="7 người" {{ old('loai_san') == '7 người' ? 'selected' : '' }}>7 người</option>
                <option value="11 người" {{ old('loai_san') == '11 người' ? 'selected' : '' }}>11 người</option>
                <option value="Futsal" {{ old('loai_san') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
            </select>
            @error('loai_san') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Giá thuê -->
        <div class="mb-3">
            <label for="gia_thue" class="form-label">Giá thuê (đ/giờ)</label>
            <input type="number" name="gia_thue" id="gia_thue" class="form-control" 
                   value="{{ old('gia_thue') }}" required min="1">
            @error('gia_thue') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Giờ hoạt động -->
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


        {{-- Danh sách gợi ý giờ 00:00 → 23:30 (30 phút/bước) --}}
        <datalist id="gio-options">
            @for ($i = 0; $i < 24; $i++)
                <option value="{{ sprintf('%02d:00', $i) }}">
                <option value="{{ sprintf('%02d:30', $i) }}">
            @endfor
        </datalist>



        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Lưu sân
        </button>
        <a href="{{ route('admin.san-bong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
