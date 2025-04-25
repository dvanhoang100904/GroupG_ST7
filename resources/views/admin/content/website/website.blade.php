@extends('admin.layout.app')
@section('page_title', 'Quản Lý Website')
@section('content')
<div class="container-fluid">
    <h4 class="mb-4 fw-bold">Quản lí đánh giá khách hàng</h4>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Tên Khách Hàng</th>
                    <th>SDT</th>
                    <th>Phân loại</th>
                    <th>Chi tiết</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Khách hàng 1</td>
                    <td>0000000</td>
                    <td><span class="badge bg-info text-dark">Tin nhắn</span></td>
                    <td><a href="#" class="btn btn-outline-success btn-sm">Chi tiết</a></td>
                    <td>
                        <button class="btn btn-warning btn-sm">Nhắn tin</button>
                        <button class="btn btn-danger btn-sm">Reply</button>
                        <!-- Nút xác nhận mở modal -->
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">Xác Nhận</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Khách hàng 2</td>
                    <td>0000000</td>
                    <td><span class="badge bg-secondary">Bình Luận</span></td>
                    <td><a href="#" class="btn btn-outline-success btn-sm">Chi tiết</a></td>
                    <td>
                        <button class="btn btn-secondary btn-sm">Testing</button>
                        <button class="btn btn-danger btn-sm">Reply</button>
                        <!-- Nút xác nhận mở modal -->
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">Xác Nhận</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Thông báo sau xác nhận -->
    <div id="statusMessage" class="alert mt-4 d-none" role="alert"></div>
</div>

<!-- Modal xác nhận -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Xác nhận trạng thái</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        Bạn muốn đánh dấu là <strong>hoàn tất</strong> hay <strong>chưa hoàn tất</strong>?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success" onclick="handleStatus('done')">Hoàn tất</button>
        <button type="button" class="btn btn-warning" onclick="handleStatus('notdone')">Chưa hoàn tất</button>
      </div>

    </div>
  </div>
</div>

<script>
  function handleStatus(status) {
    const messageEl = document.getElementById('statusMessage');
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));

    if (status === 'done') {
      messageEl.className = 'alert alert-success mt-4';
      messageEl.textContent = '✅ Đã đánh dấu là HOÀN TẤT.';
    } else {
      messageEl.className = 'alert alert-warning mt-4';
      messageEl.textContent = '⚠️ Đã đánh dấu là CHƯA HOÀN TẤT.';
    }

    messageEl.classList.remove('d-none');
    modal.hide();
  }
</script>
@endsection
