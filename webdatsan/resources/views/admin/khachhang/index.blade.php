@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-primary">
        <i class="fas fa-users me-2"></i> Quản lý khách hàng
    </h2>

    <!-- Thanh công cụ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.khach-hang.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Thêm khách hàng
        </a>

        <form method="GET" action="{{ route('admin.khach-hang.index') }}" class="d-flex" style="max-width: 300px;">
            <input type="text" name="keyword" class="form-control me-2" 
                   placeholder="Nhập tên, email hoặc mã KH..." value="{{ request('keyword') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Bảng khách hàng -->
    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-hover align-middle fs-5 rounded-3 overflow-hidden">
            <thead class="table-dark">
                <tr class="text-center" style="height: 60px; font-size: 1.1rem;">
                    <th>#</th>
                    <th>Mã KH</th>
                    <th>Tên khách hàng</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $customer)
                    <tr style="height: 65px;">
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold text-primary">{{ $customer->ma_khach_hang }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->address }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Sửa -->
                                <a href="{{ route('admin.khach-hang.edit', $customer->id) }}" 
                                class="btn btn-warning py-1 px-2" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Đặt sân -->
                                <a href="{{ route('admin.khach-hang.lich-su-dat-san', $customer->id) }}" 
                                class="btn btn-info py-1 px-2 text-white" title="Lịch sử đặt sân">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>

                                <!-- Thanh toán -->
                                <a href="{{ route('admin.khach-hang.lich-su-thanh-toan', $customer->id) }}" 
                                class="btn btn-secondary py-1 px-2" title="Lịch sử thanh toán">
                                    <i class="fas fa-receipt"></i>
                                </a>

                                <!-- Xóa -->
                                <form action="{{ route('admin.khach-hang.destroy', $customer->id) }}" 
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger py-1 px-2" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5 fs-5">
                            <i class="fas fa-inbox fs-2 mb-2"></i>
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
