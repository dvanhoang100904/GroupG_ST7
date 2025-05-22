<!-- Footer -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <h4>Về chúng tôi</h4>
      {{-- Giới thiệu ngắn gọn về công ty hoặc trang web --}}
      <p>GroupG - Trang thương mại điện tử chuyên cung cấp sản phẩm chất lượng với giá cả hợp lý.</p>
    </div>

    <div class="footer-column">
      <h4>Liên kết nhanh</h4>
      {{-- Danh sách các link điều hướng nhanh trong trang --}}
      <ul>
        <li><a href="{{ route('home') }}">Trang chủ</a></li> {{-- Link về trang chủ --}}
        <li><a href="{{ route('products.index') }}">Sản phẩm</a></li> {{-- Link trang sản phẩm --}}
        <li><a href="#">Giới thiệu</a></li> {{-- Link trang giới thiệu (chưa có route cụ thể) --}}
        <li><a href="#">Liên hệ</a></li> {{-- Link trang liên hệ (chưa có route cụ thể) --}}
      </ul>
    </div>

    <div class="footer-column">
      <h4>Hỗ trợ khách hàng</h4>
      {{-- Các link chính sách hỗ trợ khách hàng --}}
      <ul>
          <li><a href="{{ route('warranty-policy') }}">Chính sách bảo hành</a></li> {{-- Link chính sách bảo hành --}}
          <li><a href="{{ route('privacy-policy') }}">Chính sách bảo mật</a></li> {{-- Link chính sách bảo mật --}}
      </ul>
    </div>

    <div class="footer-column">
      <h4>Kết nối với chúng tôi</h4>
      {{-- Các icon mạng xã hội, liên kết mở trong tab mới --}}
      <div class="social-icons">
         <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a> {{-- Facebook --}}
         <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a> {{-- Instagram --}}
         <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a> {{-- Twitter --}}
      </div>
    </div>
  </div>

  {{-- Phần dưới cùng của footer, hiển thị bản quyền --}}
  <div class="footer-bottom">
    <p>&copy; 2025 - GroupG | Trang web thương mại điện tử</p>
  </div>
</footer>
