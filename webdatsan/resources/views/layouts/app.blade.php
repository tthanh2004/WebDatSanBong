<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BFP') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Style chung cho dropdown */
        .dropdown-menu .dropdown-item {
            font-weight: 600; /* chữ đậm */
            transition: background 0.2s, transform 0.2s;
            border-radius: 6px;
        }

        /* Hover highlight */
        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f8ff;
            transform: translateX(4px);
        }

        /* Khi đang active */
        .dropdown-menu .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
        }

        .dropdown-menu .dropdown-item.active i {
            color: #fff !important; /* icon cũng thành trắng */
        }
    </style>

</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-3">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="BFP Logo" width="80" class="me-2 rounded-3">
            </a>

            <!-- Toggle mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fs-5">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active fw-bold text-primary' : '' }}" 
                        href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dat-san') ? 'active fw-bold text-primary' : '' }}" 
                        href="{{ url('/dat-san') }}">Đặt sân</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('thanh-toan') ? 'active fw-bold text-primary' : '' }}" 
                        href="{{ url('/thanh-toan') }}">Thanh toán</a>
                    </li>
                </ul>

                <!-- User -->
                    <ul class="navbar-nav align-items-center fs-5">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold d-flex align-items-center"
                                href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2 fs-4 text-primary"></i> 
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2"
                                    aria-labelledby="userDropdown"
                                    style="min-width: 260px;">
                                    
                                    <!-- Tài khoản -->
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 rounded {{ request()->is('tai-khoan') ? 'active' : '' }}"
                                        href="{{ url('/tai-khoan') }}">
                                            <i class="fas fa-id-card me-3 text-primary fs-5"></i>
                                            <span>Tài khoản</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 rounded {{ request()->is('san-da-dat') ? 'active' : '' }}"
                                        href="{{ url('/san-da-dat') }}">
                                            <i class="fas fa-futbol me-3 text-success fs-5"></i>
                                            <span>Sân đã đặt</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 rounded {{ request()->is('thanh-toan') ? 'active' : '' }}"
                                        href="{{ url('/thanh-toan') }}">
                                            <i class="fas fa-credit-card me-3 text-warning fs-5"></i>
                                            <span>Thanh toán</span>
                                        </a>
                                    </li>


                                    <!-- Nếu là admin -->
                                    @if(Auth::user()->role === 'admin')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2 rounded"
                                            href="{{ url('/admin/khach-hang') }}">
                                                <i class="fas fa-users me-3 text-info fs-5"></i>
                                                <span>Quản lý khách hàng</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2 rounded"
                                            href="{{ url('/admin/san-bong') }}">
                                                <i class="fas fa-futbol me-3 text-success fs-5"></i>
                                                <span>Quản lý sân bóng</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2 rounded"
                                            href="{{ url('/admin/tai-khoan') }}">
                                                <i class="fas fa-user-cog me-3 text-dark fs-5"></i>
                                                <span>Quản lý tài khoản</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2 rounded {{ request()->is('admin/thanh-toan') ? 'active' : '' }}"
                                            href="{{ url('/admin/thanh-toan') }}">
                                                <i class="fas fa-credit-card me-3 text-warning fs-5"></i>
                                                <span>Quản lý thanh toán</span>
                                            </a>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    <!-- Đăng xuất -->
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-danger rounded">
                                                <i class="fas fa-sign-out-alt me-3 fs-5"></i>
                                                <span>Đăng xuất</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm me-2 fs-6" href="{{ route('login') }}">Đăng nhập</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm fs-6" href="{{ route('register') }}">Đăng ký</a>
                            </li>
                        @endauth
                    </ul>

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container my-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            &copy; {{ date('Y') }} BFP. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
