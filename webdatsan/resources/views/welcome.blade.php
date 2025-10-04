@extends('layouts.app')

@section('content')
    <!-- Banner -->
    <section class="bg-light text-center py-5 rounded shadow-sm">
        <div class="container">
            <h1 class="display-4 fw-bold">BFP - Đặt sân thể thao trực tuyến</h1>
            <p class="lead mb-4">Nhanh chóng - Tiện lợi - Uy tín</p>
            <img src="{{ asset('images/stadium.png') }}" 
                 alt="Banner" 
                 class="img-fluid rounded shadow-sm" 
                 style="max-height: 350px;">
        </div>
    </section>

    <!-- Tìm kiếm sân -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-4">Đặt sân ngay</h2>
            <form class="row g-3 justify-content-center">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sân...">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Môn thể thao</option>
                        <option>Bóng đá</option>
                        <option>Bóng rổ</option>
                        <option>Cầu lông</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Tỉnh/Thành phố</option>
                        <option>Hà Nội</option>
                        <option>Hồ Chí Minh</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Quận/Huyện</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Sân nổi bật -->
    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="mb-4">Sân bóng nổi bật</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('images/football_field.png') }}" 
                                 class="card-img-top" alt="Sân bóng">
                            <div class="card-body">
                                <h5 class="card-title">Sân bóng La Thành</h5>
                                <p class="card-text">Quận Thanh Xuân, Hà Nội</p>
                                <p class="text-muted">Mở cửa: 05:00 - 22:00</p>
                                <p class="text-warning mb-0">⭐ 0 lượt đánh giá</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ url('/dat-san') }}" class="btn btn-primary btn-sm">Đặt ngay</a>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection
