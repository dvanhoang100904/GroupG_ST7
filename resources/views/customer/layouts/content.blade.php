<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Trang Chủ')</title>
</head>

<body>
    {{-- header --}}
    @include('customer.layouts.header')

    {{-- navbar --}}
    @include('customer.layouts.navbar')

    {{-- main content --}}
    @yield('content')


    {{-- footer --}}
    @include('customer.layouts.footer')
</body>

</html>
