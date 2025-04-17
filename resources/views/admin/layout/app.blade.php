<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'Trang quản trị')</title>

    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet" />

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Font Awesome (nếu cần icon) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    {{-- CSS custom của bạn --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    <div class="page-wrapper">
        {{-- Sidebar --}}
        @include('admin.layout.sidebar')

        {{-- Header --}}
        @include('admin.layout.header')

        {{-- Nội dung chính --}}
        <div class="main-content">
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('admin.layout.footer')
    </div>

    {{-- Bootstrap JS (bundle có Popper sẵn) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
