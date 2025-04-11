<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Trang quản trị')</title>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="page-wrapper">
    @include('admin.layout.sidebar')
    
    @include('admin.layout.header')

    <div class="main-content">
      @yield('content')
    </div>

    @include('admin.layout.footer')
  </div>
</body>
</html>
