@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-success">
        <i class="fas fa-futbol me-2"></i> Quản lý sân bóng
    </h2>

    <!-- Nút thêm sân -->
    <div class="mb-3">
        <a href="{{ route('admin.san-bong.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Thêm sân bóng
        </a>
    </div>

    <!-- Bảng sân bóng -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên sân</th>
                    <th>Địa chỉ</th>
                    <th>Giá / giờ</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanBong as $index => $field)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $field->ten_san }}</td>
                        <td>{{ $field->address }}</td>
                        <td>{{ number_format($field->gia_thue, 0, ',', '.') }} đ</td>
                        <td>
                            @if($field->status === 'available')
                                <span class="badge bg-success">Còn trống</span>
                            @else
                                <span class="badge bg-danger">Đã đặt</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.san-bong.edit', $field->id) }}" 
                               class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <!-- xóa -->
                            <form action="{{ route('admin.san-bong.destroy', $field->id) }}" 
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc muốn xóa sân bóng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                            <!-- kích hoạt -->
                            <form action="{{ route('admin.san-bong.toggle-status', $field->id) }}" 
                                method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                @if($field->status === 'inactive')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-play"></i> Kích hoạt
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-pause"></i> Tạm dừng
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fs-3 mb-2"></i>
                            <div>Không có sân bóng nào</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-3">
        {{ $sanBong->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
