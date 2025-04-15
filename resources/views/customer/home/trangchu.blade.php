<!-- resources/views/review/trangchu.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - GroupG</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="/logo.png" alt="Logo" class="h-8">
            <input type="text" placeholder="Bạn cần tìm gì?" class="border px-3 py-1 rounded w-96">
        </div>
        <div class="space-x-4">
            <a href="#" class="text-sm">Giỏ hàng</a>
            <a href="#" class="text-sm">Tài khoản</a>
        </div>
    </header>

    <!-- Banner -->
    <div class="mt-4 px-4">
        <img src="/images/banner.jpg" class="w-full h-64 object-cover rounded-lg shadow">
    </div>

    <div class="hot-sale py-6">
        <h2 class="text-2xl font-bold text-center text-red-600 mb-4">Danh Sách Các Sản Phẩm</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 px-4">
            @foreach ($hotSaleProducts as $product)
                <div class="bg-white p-3 rounded-md shadow relative hover:shadow-lg transition">

                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-40 object-contain mb-2" alt="{{ $product->product_name }}">

                    <p class="font-semibold text-sm">{{ $product->product_name }}</p>
                    <p class="text-red-600 font-bold text-lg">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    <p class="text-xs text-gray-500 mt-1">Không áp dụng trả góp 0% khi mua online</p>
                    <div class="flex justify-between items-center mt-2 text-xs">
                        <button class="text-red-500">❤️ Yêu thích</button>
                        <span class="text-blue-500">Trả góp 0%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white mt-8 py-4 text-center text-sm border-t">
        © 2025 - GroupG | Trang web thương mại điện tử 
    </footer>

</body>
</html>