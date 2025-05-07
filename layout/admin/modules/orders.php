<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .actions button {
            margin-right: 5px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #888;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .modal.active {
            display: block;
        }
    </style>
</head>
<body>
    <h2>Quản lý đơn hàng</h2>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Phương thức thanh toán</th>
                <th>Trạng thái</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody id="order-table-body">
            <!-- Dữ liệu sẽ được render ở đây -->
        </tbody>
    </table>

    <!-- Modal xem chi tiết -->
    <div id="order-detail-modal" class="modal"></div>

    <!-- Modal cập nhật trạng thái -->
    <div id="order-status-modal" class="modal">
        <h3>Cập nhật trạng thái đơn hàng</h3>
        <form id="status-form">
            <input type="hidden" id="status-order-id">
            <label for="status-select">Trạng thái:</label>
            <select id="status-select">
                <option value="pending">Đang xử lý</option>
                <option value="shipped">Đã giao</option>
                <option value="cancelled">Đã hủy</option>
            </select>
            <br><br>
            <button type="submit">Lưu trạng thái</button>
        </form>
    </div>

    <script src="../../../JS/admin/order.js"></script>
    <script>
        showAll();
        function showAll() {
            getAllOrders()
                .then(result => {
                    let stt = 1;
                    const orders = result;
                    const tbody = document.querySelector("#order-table-body");
                    tbody.innerHTML = ""; // Xóa nội dung cũ
                    orders.forEach(order => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                            <td>${stt++}</td>
                            <td>${order.customerName}</td>
                            <td>${order.createdAt}</td>
                            <td>${order.totalPrice}₫</td>
                            <td>${order.paymentMethod || 'N/A'}</td>
                            <td>${order.status}</td>
                            <td class="actions">
                                <button onclick="viewDetails(${order.ID})">👁️</button>
                                <button onclick="editOrder(${order.ID})">✏️</button>
                                <button onclick="showStatusModal(${order.ID}, '${order.status}')">🔁</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách đơn hàng:', error.message);
                });
        }

        window.viewDetails = async (orderID) => {
            const detailModal = document.getElementById('order-detail-modal');
            try {
                const details = await getOrderDetails(orderID);
                if (details && details.length > 0) {
                    const detail = details[0];
                    detailModal.innerHTML = `
                        <h3>Chi tiết đơn hàng #${orderID}</h3>
                        <p><strong>Tên:</strong> ${detail.receiverName}</p>
                        <p><strong>Địa chỉ:</strong> ${detail.address}</p>
                        <p><strong>SĐT:</strong> ${detail.phone}</p>
                        <p><strong>Email:</strong> ${detail.email}</p>
                        <p><strong>Phương thức thanh toán:</strong> ${detail.paymentMethod}</p>
                        <button onclick="document.getElementById('order-detail-modal').classList.remove('active')">Đóng</button>
                    `;
                    detailModal.classList.add('active');
                } else {
                    alert('Không tìm thấy chi tiết đơn hàng');
                }
            } catch (error) {
                console.error('Lỗi khi lấy chi tiết đơn hàng:', error);
                alert('Không thể lấy chi tiết đơn hàng');
            }
        };

        window.editOrder = async (orderID) => {
            const newName = prompt('Tên người nhận mới:');
            const newAddress = prompt('Địa chỉ mới:');
            const newPhone = prompt('Số điện thoại mới:');
            const newEmail = prompt('Email mới:');
            const newPaymentMethod = prompt('ID phương thức thanh toán mới:');
            if (newName && newAddress && newPhone && newEmail && newPaymentMethod) {
                try {
                    await updateOrderDetails({
                        orderID,
                        receiverName: newName,
                        address: newAddress,
                        phone: newPhone,
                        email: newEmail,
                        paymentMethodID: newPaymentMethod
                    });
                    alert('Đã cập nhật chi tiết đơn hàng');
                    showAll();
                } catch (error) {
                    console.error('Lỗi khi cập nhật chi tiết đơn hàng:', error);
                    alert('Không thể cập nhật chi tiết đơn hàng');
                }
            }
        };

        window.showStatusModal = (orderID, currentStatus) => {
            const statusModal = document.getElementById('order-status-modal');
            const statusOrderIdInput = document.getElementById('status-order-id');
            const statusSelect = document.getElementById('status-select');
            statusOrderIdInput.value = orderID;
            statusSelect.value = currentStatus;
            statusModal.classList.add('active');
        };

        const statusForm = document.getElementById('status-form');
        statusForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const statusOrderIdInput = document.getElementById('status-order-id');
            const statusSelect = document.getElementById('status-select');
            const statusModal = document.getElementById('order-status-modal');
            const orderID = statusOrderIdInput.value;
            const newStatus = statusSelect.value;
            try {
                await updateOrderStatus(orderID, newStatus);
                alert('Đã cập nhật trạng thái thành công');
                statusModal.classList.remove('active');
                showAll();
            } catch (error) {
                console.error('Lỗi khi cập nhật trạng thái:', error);
                alert('Không thể cập nhật trạng thái');
            }
        });
    </script>
</body>
</html>