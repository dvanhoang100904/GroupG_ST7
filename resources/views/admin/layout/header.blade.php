<!-- Header -->
<div class="navbar">
  <!-- Tiêu đề Dashboard -->
  <div class="navbar-title">
    <h2>@yield('page_title', 'Dashboard')</h2>
  </div>  

  <!-- Ô tìm kiếm -->
  <div class="search-box">
    <input type="text" placeholder="Tìm kiếm..." />
    <button><img src="{{ asset('images/search.png') }}" alt="Search Icon" /></button>
  </div>

  <!-- Logo -->
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" />
  </div>
</div>

