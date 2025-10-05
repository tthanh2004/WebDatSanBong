@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Danh sách giao dịch</h2>

    @if($giaoDich->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa có giao dịch nào.</div>
    @else
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Sân</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Phương thức</th>
                </tr>
            </thead>
            <tbody>
                @foreach($giaoDich as $gd)
                    <tr>
                        <td>{{ $gd->sanBong->ten_san}}</td>
                        <td>{{ number_format($gd->so_tien, 0, ',', '.') }} VND</td>
                        <td>
                            @if($gd->trang_thai === 'pending')
                                <span class="badge bg-warning">Đang xử lý</span>
                            @elseif($gd->trang_thai === 'success')
                                <span class="badge bg-success">Thành công</span>
                            @else
                                <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </td>
                        <td>{{ mb_strtoupper($gd->phuong_thuc, 'UTF-8') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
