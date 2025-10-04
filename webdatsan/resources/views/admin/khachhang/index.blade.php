@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-primary">
        <i class="fas fa-users me-2"></i> Quản lý khách hàng
    </h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Nút thêm khách hàng -->
            <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Thêm khách hàng
            </a>

            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('admin.khach-hang.index') }}" 
                    class="d-flex" style="max-width: 280px;">
                    <input type="text" name="keyword" class="form-control me-2" 
                        placeholder="Nhập tên hoặc email..." style="width: 180px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
            </form>
        </div>

    <!-- Bảng khách hàng -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.khach-hang.edit', $customer->id) }}" 
                               class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.khach-hang.destroy', $customer->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fs-3 mb-2"></i>
                            <div>Không có khách hàng nào</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
