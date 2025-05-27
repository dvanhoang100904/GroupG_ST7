<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'Trang quản trị')</title>

    {{-- google fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- font-awesome icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- css admin --}}
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
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
</body>

</html>
