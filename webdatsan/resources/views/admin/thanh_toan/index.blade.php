@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center text-primary">
        <i class="fas fa-credit-card"></i> Quản lý thanh toán
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-hover text-center align-middle shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Khách hàng</th>
                <th>Sân</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày yêu cầu</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($giaoDich as $gd)
                <tr>
                    <td>{{ $gd->user->name ?? 'N/A' }}</td>
                    <td>{{ $gd->sanBong->ten_san ?? '' }}</td>
                    <td>{{ number_format($gd->so_tien, 0, ',', '.') }} VND</td>
                    <td>{{ $gd->phuong_thuc }}</td>
                    <td>
                        @if($gd->trang_thai === 'pending')
                            <span class="badge bg-warning">Chờ xử lý</span>
                        @elseif($gd->trang_thai === 'success')
                            <span class="badge bg-success">Thành công</span>
                        @else
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td>{{ $gd->created_at ? $gd->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        @if($gd->trang_thai === 'pending')
                            <form action="{{ route('admin.thanh-toan.approve', $gd->_id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Duyệt
                                </button>
                            </form>
                            <form action="{{ route('admin.thanh-toan.reject', $gd->_id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>
                            </form>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Chưa có giao dịch nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
