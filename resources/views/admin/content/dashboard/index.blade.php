@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="container mt-5">
    <h2 class="mb-4">Thống kê tổng quan</h2>

    <div class="row g-4">
        <!-- Sản phẩm -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-2x mb-2"></i>
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="card-text fs-4">{{ $productCount }}</p>
                </div>
            </div>
        </div>

        <!-- Đơn hàng -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="card-text fs-4">{{ $orderCount }}</p>
                </div>
            </div>
        </div>

        <!-- Admin -->
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-shield fa-2x mb-2"></i>
                    <h5 class="card-title">Admin</h5>
                    <p class="card-text fs-4">{{ $adminCount }}</p>
                </div>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h5 class="card-title">Khách hàng</h5>
                    <p class="card-text fs-4">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
