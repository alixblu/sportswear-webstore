<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!--=============== REMIXICONS ===============-->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <title>Quản lý đơn hàng</title>
  <style>
    /* Common styles */
    .main-content {
      padding: 20px;
    }

    .page-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .title {
      font-size: 24px;
      font-weight: 600;
      color: #2d3748;
    }

    .action-buttons .btn {
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      color: white;
      border: none;
    }

    .stats-cards {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .card-title h3 {
      font-size: 18px;
      color: #4a5568;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }

    .data-table th {
      background-color: #f7fafc;
      color: #4a5568;
      font-weight: 600;
    }

    .data-table tr:hover {
      background-color: #f8fafc;
    }

    .btn-outline {
      background: transparent;
      border: 1px solid #e2e8f0;
      color: #4a5568;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .btn-outline:hover {
      background-color: #f8fafc;
    }

    .btn-sm {
      font-size: 13px;
      padding: 5px 10px;
    }

    /* Status styles */
    .status {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .status.pending {
      background-color: rgba(234, 179, 8, 0.15);
      color: #d97706;
      border: 1px solid rgba(234, 179, 8, 0.3);
    }

    .status.approved {
      background-color: rgba(59, 130, 246, 0.15);
      color: #2563eb;
      border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status.delivered {
      background-color: rgba(16, 185, 129, 0.15);
      color: #059669;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status.canceled {
      background-color: rgba(239, 68, 68, 0.15);
      color: #dc2626;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status.active {
      background-color: rgba(76, 201, 240, 0.15);
      color: #0891b2;
      border: 1px solid rgba(76, 201, 240, 0.3);
    }

    /* Filter styles */
    .filter-container {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .filter-container select,
    .filter-container input {
      padding: 8px 12px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      outline: none;
    }

    .filter-container button {
      padding: 8px 16px;
      background: #3b82f6;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .filter-container button:hover {
      background: #2563eb;
    }

    /* Modal styles (aligned with coupon.php) */
    #portal-root {
      position: fixed;
      top: 0;
      left: 130px;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }

    .formUserCss {
      background-color: white;
      max-width: 500px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      font-family: 'Poppins', sans-serif;
    }

    .wrapperCss {
      padding: 0 30px;
      padding-bottom: 30px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .wrapperInputCss {
      display: flex;
      flex-direction: column;
      background: rgba(255, 255, 255, 0.1);
      transition: border-bottom 0.3s ease;
      border-bottom: 1px solid silver;
      padding: 5px 3px;
    }

    .wrapperInputCss:focus-within {
      border-bottom: 1px solid #00e5ff;
    }

    .inputUserCss {
      border: none;
      outline: none;
      color: #2d3748;
      font-size: 17px;
    }

    .buttonUserCss {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
    }

    .buttonUserCss:hover {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      box-shadow: 0 6px 15px rgba(58, 12, 163, 0. styled-components);
    }

    .CloseCss {
      display: flex;
      justify-content: flex-end;
      padding: 10px;
    }

    /* Toast styles */
    #toast-portal {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      z-index: 9999;
    }

    .toast {
      min-width: 250px;
      padding: 12px 18px;
      color: #fff;
      border-radius: 8px;
      font-size: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInOut 3s ease forwards;
    }

    .toast.success {
      background-color: #4caf50;
    }

    .toast.error {
      background-color: #f44336;
    }

    @keyframes fadeInOut {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }

      10% {
        opacity: 1;
        transform: translateY(0);
      }

      90% {
        opacity: 1;
        transform: translateY(0);
      }

      100% {
        opacity: 0;
        transform: translateY(20px);
      }
    }
  </style>
</head>

<body>
  <div class="main-content">
    <div id="pageTitle" class="page-title">
      <div class="title">Quản lý đơn hàng</div>
    </div>

    <div class="stats-cards">
      <div class="table-card">
        <div class="card-title">
          <h3><i class="fa-solid fa-clipboard-list"></i> Danh sách đơn hàng</h3>
          <div class="filter-container">
            <select id="filter-status">
              <option value="">Tất cả trạng thái</option>
              <option value="pending">Chưa xác nhận</option>
              <option value="approved">Đã xác nhận</option>
              <option value="delivered">Đã giao</option>
              <option value="canceled">Hủy đơn</option>
            </select> 
            <input type="text" id="address" name="address" placeholder="Nhập Địa chỉ giao hàng">
            <input type="date" id="from-date" placeholder="Từ ngày">
            <input type="date" id="to-date" placeholder="Đến ngày">
            <button onclick="applyFilter()">Lọc</button>
          </div>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Mã đơn</th>
              <th>Khách hàng</th>
              <th>Ngày đặt</th>
              <th>Tổng tiền</th>
              <th>Phương thức TT</th>
              <th>Trạng thái</th>
              <th>Địa chỉ giao hàng </th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="order-table-body">
            <!-- Orders will be populated dynamically -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="toast-portal"></div>

  <script src="/sportswear-webstore/JS/admin/order.js"></script>
  <script>
    // Valid status transitions
    const statusTransitions = {
      'pending': ['approved', 'canceled'],
      'approved': ['delivered', 'canceled'],
      'delivered': [],
      'canceled': []
    };

    // Load orders when page loads
    document.addEventListener('DOMContentLoaded', function() {
      loadOrders();
    });

    // Function to load orders with optional filters
    function loadOrders(status = '',address = '' ,fromDate = '', toDate = '') {
      searchOrders({ status,address ,fromDate, toDate })
        .then(result => {
          const orders = Array.isArray(result) ? result : result.data;
          const tbody = document.querySelector("#order-table-body");
          tbody.innerHTML = "";

          orders.forEach(order => {
            const tr = document.createElement("tr");
            const statusClass = getStatusClass(order.status);
            tr.innerHTML = `
              <td>${order.ID}</td>
              <td>${order.customerName}</td>
              <td>${order.createdAt}</td>
              <td>${order.totalPrice}₫</td>
              <td>${getPaymentMethodText(order.paymentMethod)}</td>
              <td><span class="status ${statusClass}">${getStatusText(order.status)}</span></td>
              <td>${order.address}</td>
              <td>
                <button class="btn btn-outline btn-sm" onclick="editStatus('${order.ID}', '${order.status}')">
                  <i class="fas fa-edit"></i> Cập nhật
                </button>
                
              </td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(error => {
          console.error('Lỗi khi lấy danh sách đơn hàng:', error.message);
          showToast('Không thể tải danh sách đơn hàng: ' + error.message, 'error');
        });
    }

    // Apply filter button click handler
    function applyFilter() {
      const status = document.getElementById('filter-status').value;
      const fromDate = document.getElementById('from-date').value;
      const toDate = document.getElementById('to-date').value;
      const address = document.getElementById('address').value;
      console.log('Address:', address);
      if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
        showToast("Ngày bắt đầu không thể lớn hơn ngày kết thúc!", 'error');
        return;
      }
      
      loadOrders(status, address,fromDate, toDate);
    }

    // Get status class for order
    function getStatusClass(status) {
      switch (status) {
        case 'pending':
          return 'pending';
        case 'approved':
          return 'approved';
        case 'delivered':
          return 'delivered';
        case 'canceled':
          return 'canceled';
        default:
          return '';
      }
    }

    // Convert status to display text
    function getStatusText(status) {
      switch (status) {
        case 'pending':
          return 'Chưa xác nhận';
        case 'approved':
          return 'Đã xác nhận';
        case 'delivered':
          return 'Đã giao';
        case 'canceled':
          return 'Hủy đơn';
        default:
          return status;
      }
    }

    // Convert payment method to display text
    function getPaymentMethodText(method) {
      switch (method) {
        case 'Cash':
          return 'Tiền mặt';
        case 'Credit/Debit Card':
          return 'Thẻ tín dụng/thẻ ghi nợ';
        case 'Bank Transfer':
          return 'Chuyển khoản ngân hàng';
        default:
          return method || 'N/A';
      }
    }

    // Show update status modal
    function showStatusModal(orderId, currentStatus) {
      if (['delivered', 'canceled'].includes(currentStatus)) {
        showToast("Không thể cập nhật trạng thái này!", 'error');
        return;
      }

      // Get valid status transitions
      const validStatuses = statusTransitions[currentStatus] || [];

      // Create portal root for modal
      const portalRoot = document.createElement('div');
      portalRoot.id = 'portal-root';
      portalRoot.innerHTML = `
        <div class="formUserCss">
          <div class="CloseCss">
            <i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i>
          </div>
          <div class="wrapperCss">
            <h3 for="status-select">Cập nhật trạng thái có mã đơn ${orderId} :</h3>
            <label for="status-select">Trạng thái :</label>
            <div class="wrapperInputCss">
              <select class="inputUserCss" id="status-select">
                ${validStatuses.map(status => `
                  <option value="${status}">${getStatusText(status)}</option>
                `).join('')}
              </select>
            </div>
            <input type="hidden" id="status-order-id" value="${orderId}">
            <input type="hidden" id="current-status" value="${currentStatus}">
            <div class="wrapperButton">
              <input class="buttonUserCss" type="submit" value="Lưu trạng thái" onclick="updateOrderStatusFromForm()">
            </div>
          </div>
        </div>
      `;
      document.body.appendChild(portalRoot);
    }

    // Close modal
    function closeModal() {
      const portalRoot = document.getElementById('portal-root');
      if (portalRoot) {
        portalRoot.remove();
      }
      loadOrders();
    }

    // Update order status from form
    function updateOrderStatusFromForm() {
      const ID = document.getElementById('status-order-id').value.trim();
      const status = document.getElementById('status-select').value.trim();
      const currentStatus = document.getElementById('current-status').value.trim();

      if (!statusTransitions[currentStatus].includes(status)) {
        showToast("Trạng thái không hợp lệ!", 'error');
        return;
      }

      updateOrderStatus(ID, status)
        .then(response => {
          if (response.success) {
            showToast('Trạng thái đơn hàng đã được cập nhật!', 'success');
            closeModal();
            // Reload orders with current filters
            const currentStatus = document.getElementById('filter-status').value;
            const fromDate = document.getElementById('from-date').value;
            const toDate = document.getElementById('to-date').value;
            loadOrders(currentStatus, fromDate, toDate);
          } else {
            showToast('Cập nhật trạng thái đơn hàng thành công ');
          }
        })
        .catch(error => {
          console.error('Lỗi khi cập nhật trạng thái:', error.message, error.stack);
          showToast(`Cập nhật thất bại: ${error.message}`, 'error');
        });
    }

    // Edit status handler
    function editStatus(ID, currentStatus) {
      showStatusModal(ID, currentStatus);
    }

    // Show toast message
    function showToast(text, type = 'success') {
      let portalRoot = document.getElementById('toast-portal');

      if (!portalRoot) {
        portalRoot = document.createElement('div');
        portalRoot.id = 'toast-portal';
        document.body.appendChild(portalRoot);
      }

      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.innerText = text;

      portalRoot.appendChild(toast);

      setTimeout(() => {
        toast.remove();
        if (portalRoot.children.length === 0) {
          portalRoot.remove();
        }
      }, 3000);
    }
    
  </script>
</body>

</html>