<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đơn hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* (Toàn bộ phần CSS bạn đã viết giữ nguyên, không thay đổi để tiết kiệm không gian) */
  </style>
</head>
<body>
  <div class="main-content">
    <div id="pageTitle" class="page-title">
      <div class="title">Quản lý đơn hàng</div>
      <div class="action-buttons">
        <button id="exportBtn" class="btn btn-outline">
          <i class="fas fa-download"></i> Xuất Excel
        </button>
      </div>
    </div>
    <div class="stats-cards">
      <div class="table-card">
        <div class="card-title">
          <h3><i class="fa-solid fa-clipboard-list"></i> Danh sách đơn hàng</h3>
          <div class="wrapperFilter">
            <div class="search-box">
              <i class="ri-search-line"></i>
              <input type="text" id="searchInput" placeholder="Tìm kiếm theo mã đơn hàng">
            </div>
            <button class="btn btn-outline btn-sm" onclick="showFormFilter()">
              <i class="fa-solid fa-filter"></i> Bộ lọc
            </button>
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
              <th class="actionOrder">Thao tác</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            <!-- Dữ liệu sẽ được load từ JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="../../JS/admin/order.js"></script>
  <script>
    showAll();

    function showAll() {
      getAllOrder()
        .then(result => {
          const tbody = document.querySelector(".data-table tbody");
          tbody.innerHTML = "";
          result.forEach(order => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${order.orderID}</td>
              <td>${order.customerName}</td>
              <td>${formatDate(order.orderDate)}</td>
              <td>${Number(order.totalPrice).toLocaleString('vi-VN')} ₫</td>
              <td>${order.paymentMethod || 'N/A'}</td>
              <td><span class="status ${order.status}">${getStatusText(order.status)}</span></td>
              <td class="actionOrder">
                <button class="btn btn-outline btn-sm" onclick="viewOrderDetails(${order.orderID})">
                  <i class="fa-solid fa-eye"></i> Xem
                </button>
                <button class="btn btn-outline btn-sm" onclick="showFormEditStatus(this, ${order.orderID})">
                  <i class="fa-solid fa-pen"></i> Sửa
                </button>
              </td>`;
            tbody.appendChild(tr);
          });
        })
        .catch(error => {
          console.error('Lỗi khi lấy danh sách đơn hàng:', error.message);
        });
    }

    async function viewOrderDetails(orderId) {
      try {
        const orderDetails = await getOrderById(orderId);
        if (!Array.isArray(orderDetails) || orderDetails.length === 0) {
          throw new Error('Không tìm thấy chi tiết đơn hàng');
        }
        const order = orderDetails[0];
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
          <div class="formOrderCss">
            <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()" style="cursor: pointer;"></i></div>
            <div class="wrapperCss">
              <div class="infoCss">Chi tiết đơn hàng #${orderId}</div>
              <div><strong>Khách hàng:</strong> ${order.customerName}</div>
              <div><strong>Ngày đặt:</strong> ${formatDate(order.orderDate)}</div>
              <div><strong>Tổng tiền:</strong> ${Number(order.totalPrice).toLocaleString('vi-VN')} ₫</div>
              <div><strong>Phương thức TT:</strong> ${order.paymentMethod || 'N/A'}</div>
              <div><strong>Trạng thái:</strong> ${getStatusText(order.status)}</div>
              <div><strong>Người nhận:</strong> ${order.receiverName || 'N/A'}</div>
              <div><strong>Địa chỉ:</strong> ${order.address || 'N/A'}</div>
              <div><strong>SĐT:</strong> ${order.phone || 'N/A'}</div>
              <div><strong>Email:</strong> ${order.email || 'N/A'}</div>
              <h4>Sản phẩm:</h4>
              <ul>
                ${orderDetails.map(item => `
                  <li>${item.productName} (${item.productCode}) - SL: ${item.quantity} - Giá: ${Number(item.unitPrice).toLocaleString('vi-VN')} ₫</li>
                `).join('')}
              </ul>
            </div>
          </div>`;
        document.body.appendChild(portalRoot);
      } catch (error) {
        console.error('Lỗi khi lấy chi tiết đơn hàng:', error);
        showToast('Không thể tải chi tiết đơn hàng: ' + error.message, 'error');
      }
    }

    function showFormEditStatus(button, orderId) {
      const row = button.closest('tr');
      const currentStatus = row.children[5].querySelector('.status').classList[1];
      const portalRoot = document.createElement('div');
      portalRoot.id = 'portal-root';
      portalRoot.innerHTML = `
        <div class="formOrderCss">
          <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()" style="cursor: pointer;"></i></div>
          <div class="wrapperCss">
            <div class="infoCss">Cập nhật trạng thái đơn hàng #${orderId}</div>
            <div><strong>Khách hàng:</strong> ${row.children[1].innerText.trim()}</div>
            <div><strong>Ngày đặt:</strong> ${row.children[2].innerText.trim()}</div>
            <div><strong>Tổng tiền:</strong> ${row.children[3].innerText.trim()}</div>
            <label for="status">Trạng thái:</label>
            <div class="wrapperInputCss">
              <select class="selectOrder" id="status">
                <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Chờ xử lý</option>
                <option value="confirmed" ${currentStatus === 'confirmed' ? 'selected' : ''}>Đã xác nhận</option>
                <option value="shipping" ${currentStatus === 'shipping' ? 'selected' : ''}>Đang giao</option>
                <option value="delivered" ${currentStatus === 'delivered' ? 'selected' : ''}>Đã giao</option>
                <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Đã hủy</option>
              </select>
            </div>
            <label for="note">Ghi chú:</label>
            <div class="wrapperInputCss">
              <textarea class="inputOrderCss" id="note" rows="3"></textarea>
            </div>
            <div class="wrapperButton">
              <button class="buttonOrderCss" onclick="submitStatusUpdate(${orderId})">Lưu thay đổi</button>
            </div>
          </div>
        </div>`;
      document.body.appendChild(portalRoot);
    }

    async function submitStatusUpdate(orderId) {
      const status = document.getElementById('status').value;
      const note = document.getElementById('note').value.trim();
      try {
        await updateOrderStatus(orderId, status);
        showToast(`Đã cập nhật trạng thái đơn hàng #${orderId} thành: ${getStatusText(status)}`, 'success');
        closeForm();
        showAll();
      } catch (error) {
        console.error('Lỗi khi cập nhật trạng thái:', error);
        showToast('Lỗi khi cập nhật trạng thái: ' + error.message, 'error');
      }
    }

    function getStatusText(status) {
      switch (status) {
        case 'pending': return 'Chờ xử lý';
        case 'confirmed': return 'Đã xác nhận';
        case 'shipping': return 'Đang giao';
        case 'delivered': return 'Đã giao';
        case 'cancelled': return 'Đã hủy';
        default: return status;
      }
    }

    function closeForm() {
      const portalRoot = document.getElementById('portal-root');
      if (portalRoot) portalRoot.remove();
    }
  </script>
</body>
</html>
