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
        .actionOrder {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .wrapperFilter {
            max-width: 400px;
            width: 70%;
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .formOrderCss {
            background-color: white;
            max-width: 700px;
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
        .selectOrder {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d1d1;
            border-radius: 6px;
            outline: none;
        }
        .buttonOrderCss {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }
        .buttonOrderCss:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            box-shadow: 0 6px 15px rgba(58, 12, 163, 0.2);
        }
        .wrapperButton {
            display: flex;
            gap: 10px;
        }
        .CloseCss {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            cursor: pointer;
        }
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
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
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
        .modal {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        .status-dropdown {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .modal button[type="submit"] {
            padding: 10px;
            background: linear-gradient(135deg, #6b7280, #3b82f6);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .modal button[type="submit"]:hover {
            background: linear-gradient(135deg, #4b5563, #2563eb);
        }
        .filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
        .filter-container select, .filter-container input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .filter-container button {
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
                            <th class="actionOrder">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="order-table-body">
                        <!-- Orders will be populated dynamically -->
                    </tbody>
                </table>
                <div id="modal-container"></div>
            </div>
        </div>
    </div>
    <script src="../../../JS/admin/order.js"></script>
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
    function loadOrders(status = '', fromDate = '', toDate = '') {
        searchOrders({ status, fromDate, toDate })
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
                        <td>${order.paymentMethod || 'N/A'}</td>
                        <td><span class="status ${statusClass}">${getStatusText(order.status)}</span></td>
                        <td class="actions">
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
                alert('Không thể tải danh sách đơn hàng: ' + error.message);
            });
    }

    // Apply filter button click handler
    function applyFilter() {
        const status = document.getElementById('filter-status').value;
        const fromDate = document.getElementById('from-date').value;
        const toDate = document.getElementById('to-date').value;
        
        if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
            alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc!");
            return;
        }
        
        loadOrders(status, fromDate, toDate);
    }

    // Lấy lớp trạng thái cho đơn hàng
    function getStatusClass(status) {
        switch (status) {
            case 'pending': return 'pending';
            case 'approved': return 'approved';
            case 'delivered': return 'delivered';
            case 'canceled': return 'canceled';
            default: return '';
        }
    }

    // Chuyển đổi trạng thái sang văn bản hiển thị
    function getStatusText(status) {
        switch (status) {
            case 'pending': return 'Chưa xác nhận';
            case 'approved': return 'Đã xác nhận';
            case 'delivered': return 'Đã giao';
            case 'canceled': return 'Hủy đơn';
            default: return status;
        }
    }

    // Hiển thị modal cập nhật trạng thái
    function showStatusModal(orderId, currentStatus) {
        if (['delivered', 'canceled'].includes(currentStatus)) {
            alert("Không thể cập nhật trạng thái này!");
            return;
        }

        // Lấy danh sách trạng thái hợp lệ
        const validStatuses = statusTransitions[currentStatus] || [];

        const modalHTML = `
            <div id="order-status-modal" class="modal">
                <div class="modal-close" onclick="closeModal()">×</div>
                <h3>Cập nhật trạng thái đơn hàng</h3>
                <form id="status-form">
                    <input type="hidden" id="status-order-id" value="${orderId}">
                    <input type="hidden" id="current-status" value="${currentStatus}">
                    <label for="status-select">Trạng thái:</label>
                    <select id="status-select" class="status-dropdown">
                        ${validStatuses.map(status => `
                            <option value="${status}">${getStatusText(status)}</option>
                        `).join('')}
                    </select>
                    <br><br>
                    <button type="submit">Lưu trạng thái</button>
                </form>
            </div>
        `;
        const modalContainer = document.getElementById('modal-container');
        modalContainer.innerHTML = modalHTML;

        const modal = document.getElementById('order-status-modal');
        modal.style.display = 'flex';

        // Add event listener for form submission
        document.getElementById('status-form').addEventListener('submit', function(e) {
            e.preventDefault();
            updateOrderStatusFromForm();
        });
    }

    // Đóng modal
    function closeModal() {
         loadOrders();
        const modalContainer = document.getElementById('modal-container');
        modalContainer.innerHTML = '';
    }

    // Cập nhật trạng thái đơn hàng từ form
    function updateOrderStatusFromForm() {
        const ID = document.getElementById('status-order-id').value.trim();
        const status = document.getElementById('status-select').value.trim();
        const currentStatus = document.getElementById('current-status').value.trim();

        if (!statusTransitions[currentStatus].includes(status)) {
            alert("Trạng thái không hợp lệ!");
            return;
        }

        updateOrderStatus(ID, status)
            .then(response => {
                if (response.success) {
                    alert('Trạng thái đơn hàng đã được cập nhật!');                  
                    closeModal();                
                    // Reload orders with current filters
                    const currentStatus = document.getElementById('filter-status').value;
                    const fromDate = document.getElementById('from-date').value;
                    const toDate = document.getElementById('to-date').value;
                    loadOrders(currentStatus, fromDate, toDate);
                } else {
                    alert('Cập nhật thất  ' + (response.message || 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật trạng thái:', error.message, error.stack);
                alert(`Cập nhật: ${error.message}`);
            });
    }

    // Hàm chỉnh sửa trạng thái đơn hàng
    function editStatus(ID, currentStatus) {
        showStatusModal(ID, currentStatus);
    }
    </script>
</body>
</html>