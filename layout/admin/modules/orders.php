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
        .search-box input {
            flex: 1;
            border: none;          
            background-color: transparent; 
            outline: none;         
            color: #2d3748;
        }
        .search-box {
            display: flex;
            align-items: center;
            justify-content: space-around;
            column-gap: .3rem;
            border: 1px solid #ccc;
            border-radius: 100px;
            height: 1.8rem;
            padding-left: 7px;
            max-width: 500px;
            width: 70%;
            padding: 20px;
        }
        .search-box:focus-within {
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
            border-color: rgba(67, 97, 238, 0.3);
        }
        .wrapperFilter {
            max-width: 400px;
            width: 70%;
            display: flex;
            gap: 20px;
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
        .inputOrderCss {
            border: none;
            outline: none;
            color: #2d3748;
            font-size: 17px;
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
        .infoCss {
            margin-bottom: 15px;
            font-weight: bold; 
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
        .status.processing {
            background-color: rgba(59, 130, 246, 0.15);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .status.completed {
            background-color: rgba(16, 185, 129, 0.15);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .status.cancelled {
            background-color: rgba(239, 68, 68, 0.15);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .order-details {
            margin-top: 20px;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-items th, .order-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-items th {
            background-color: #f2f2f2;
        }
        .order-summary {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
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
                    <div class="wrapperFilter">
                        <div class="search-box">
                            <i class="ri-search-line"></i>
                            <input type="text" placeholder="Tìm kiếm theo mã đơn hàng">
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
    // Hiển thị danh sách đơn hàng
    function showAll() {
        getAllOrders()
            .then(result => {
                const orders = result;
                const tbody = document.querySelector("#order-table-body");
                tbody.innerHTML = ""; // Xóa nội dung cũ

                orders.forEach(order => {
                    const tr = document.createElement("tr");
                    const statusClass = getStatusClass(order.status);
                    tr.innerHTML = `
                        <td>${order.ID}</td>
                        <td>${order.customerName}</td>
                        <td>${order.createdAt}</td>
                        <td>${order.totalPrice}₫</td>
                        <td>${order.paymentMethod || 'N/A'}</td>
                        <td><span class="status ${statusClass}">${order.status}</span></td>
                        <td class="actions">
                            <button class="btn btn-outline btn-sm" onclick="viewOrderDetails('${order.ID}')">
                                <i class="fas fa-eye"></i> Xem
                            </button>
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

    // Lấy lớp trạng thái cho đơn hàng
    function getStatusClass(status) {
        switch (status) {
            case 'pending': return 'pending';
            case 'approved': return 'processing';
            case 'delivered': return 'completed';
            case 'canceled': return 'cancelled';
            default: return '';
        }
    }

    // Hiển thị modal cập nhật trạng thái
    function showStatusModal(orderId, currentStatus) {
        if (['delivered', 'canceled'].includes(currentStatus)) {
            alert("Không thể cập nhật trạng thái này!");
            return;
        }

        const modalHTML = `
            <div id="order-status-modal" class="modal">
                <div class="modal-close" onclick="closeModal()">×</div>
                <h3>Cập nhật trạng thái đơn hàng</h3>
                <form id="status-form">
                    <input type="hidden" id="status-order-id" value="${orderId}">
                    <input type="hidden" id="current-status" value="${currentStatus}">
                    <label for="status-select">Trạng thái:</label>
                    <select id="status-select" class="status-dropdown">
                        <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Đang xử lý</option>
                        <option value="approved" ${currentStatus === 'approved' ? 'selected' : ''}>Đã xác nhận</option>
                        <option value="delivered" ${currentStatus === 'delivered' ? 'selected' : ''}>Đã giao</option>
                        <option value="canceled" ${currentStatus === 'canceled' ? 'selected' : ''}>Đã hủy</option>
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
        document.getElementById('status-form').addEventListener('submit', updateOrderStatusFromForm);
    }

    // Đóng modal
    function closeModal() {
        const modalContainer = document.getElementById('modal-container');
        modalContainer.innerHTML = '';
    }

    // Cập nhật trạng thái đơn hàng từ form
    function updateOrderStatusFromForm(event) {
        event.preventDefault();

        const Id = document.getElementById('status-order-id').value.trim();
        const status = document.getElementById('status-select').value.trim();
        const currentStatus = document.getElementById('current-status').value.trim();

        if (currentStatus === 'delivered' || currentStatus === 'canceled') {
            alert("Không thể cập nhật trạng thái này nữa!");
            closeModal();
            return;
        }

        updateOrderStatus(Id, status)
            .then(response => {
                if (response.success) {
                    alert('Trạng thái đơn hàng đã được cập nhật!');
                    closeModal();
                    showAll();
                } else {
                    alert('Cập nhật thất bại: ' + (response.message || 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật trạng thái:', error.message, error.stack);
                alert(`Cập nhật thất bại: ${error.message}`);
            });
    }

    // Hàm chỉnh sửa trạng thái đơn hàng
    function editStatus(orderId, currentStatus) {
        showStatusModal(orderId, currentStatus);
    }

    // Hàm xem chi tiết đơn hàng (placeholder)
    function viewOrderDetails(orderId) {
        console.log('Viewing order details for:', orderId);
        // Implement view order details logic here
    }

    // Hàm hiển thị form bộ lọc (placeholder)
    function showFormFilter() {
        console.log('Showing filter form');
        // Implement filter form logic here
    }

    // Gọi hàm để hiển thị danh sách đơn hàng khi trang được tải
    showAll();
    </script>
</body>
</html>