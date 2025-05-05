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
        border: none;          /* Remove all borders */
        background-color: transparent; /* Make the background transparent */
        outline: none;         /* Remove outline when focused */
        color:#2d3748
        }
        .search-box {
        display: flex;
        align-items: center;
        justify-content:space-around;
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
        .wrapperFilter{
            max-width: 400px;
            width: 70%;
            display: flex;
            gap: 20px;
        }

        .formOrderCss{
            background-color:white;
            max-width: 700px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .wrapperCss{
            padding: 0 30px;
            padding-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap:10px
        }
        
        .inputOrderCss{
            border: none;
            outline: none;
            color: #2d3748;
            font-size: 17px;
        }
        .wrapperInputCss{
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
        .wrapperButton{
            display: flex;
            gap: 10px
        }
        .CloseCss{
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            cursor:pointer;
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
        .infoCss{
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
                        <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Hàm để lấy danh sách đơn hàng từ server
        function fetchOrders() {
            fetch('../../api/get_orders.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('orderTableBody');
                    tbody.innerHTML = '';

                    data.forEach(order => {
                        const row = document.createElement('tr');
                        
                        // Format số tiền
                        const tongTien = new Intl.NumberFormat('vi-VN').format(order.tong_tien) + 'đ';
                        
                        // Format ngày đặt
                        const ngayDat = new Date(order.ngay_dat).toLocaleDateString('vi-VN');
                        
                        // Xác định trạng thái và class CSS
                        let statusClass = '';
                        let statusText = '';
                        switch(order.trang_thai) {
                            case 'pending':
                                statusClass = 'pending';
                                statusText = 'Chờ xử lý';
                                break;
                            case 'processing':
                                statusClass = 'processing';
                                statusText = 'Đang xử lý';
                                break;
                            case 'completed':
                                statusClass = 'completed';
                                statusText = 'Hoàn thành';
                                break;
                            case 'cancelled':
                                statusClass = 'cancelled';
                                statusText = 'Đã hủy';
                                break;
                        }

                        row.innerHTML = `
                            <td>${order.ma_don_hang}</td>
                            <td>${order.ten_khach_hang}</td>
                            <td>${ngayDat}</td>
                            <td>${tongTien}</td>
                            <td>${order.phuong_thuc_thanh_toan}</td>
                            <td><span class="status ${statusClass}">${statusText}</span></td>
                            <td>
                                <button class="btn btn-outline btn-sm" onclick="viewOrderDetails('${order.ma_don_hang}')">
                                    <i class="fas fa-eye"></i> Xem
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="updateOrderStatus(this, '${order.ma_don_hang}')">
                                    <i class="fas fa-edit"></i> Cập nhật
                                </button>
                            </td>
                        `;
                        
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi tải danh sách đơn hàng');
                });
        }

        // Hàm xem chi tiết đơn hàng
        function viewOrderDetails(orderId) {
            fetch(`../../api/get_order_details.php?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    const portalRoot = document.createElement('div');
                    portalRoot.id = 'portal-root';
                    
                    let orderItemsHtml = '';
                    data.items.forEach(item => {
                        const thanhTien = item.so_luong * item.don_gia;
                        orderItemsHtml += `
                            <tr>
                                <td>${item.ten_san_pham}</td>
                                <td>${item.so_luong}</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(item.don_gia)}đ</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(thanhTien)}đ</td>
                            </tr>
                        `;
                    });

                    portalRoot.innerHTML = `
                        <div class="formOrderCss">
                            <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()"></i></div>
                            <div class="wrapperCss">
                                <div class="infoCss">Chi tiết đơn hàng #${orderId}</div>
                                <div><strong>Khách hàng:</strong> ${data.ten_khach_hang}</div>
                                <div><strong>Ngày đặt:</strong> ${new Date(data.ngay_dat).toLocaleDateString('vi-VN')}</div>
                                <div><strong>Địa chỉ giao hàng:</strong> ${data.dia_chi_giao_hang}</div>
                                <div><strong>Số điện thoại:</strong> ${data.so_dien_thoai}</div>
                                <div><strong>Phương thức thanh toán:</strong> ${data.phuong_thuc_thanh_toan}</div>
                                
                                <div class="order-details">
                                    <table class="order-items">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Đơn giá</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${orderItemsHtml}
                                        </tbody>
                                    </table>
                                    <div class="order-summary">
                                        <div>Tổng tiền: ${new Intl.NumberFormat('vi-VN').format(data.tong_tien)}đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(portalRoot);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi lấy chi tiết đơn hàng');
                });
        }

        // Hàm cập nhật trạng thái đơn hàng
        function updateOrderStatus(button, orderId) {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            
            portalRoot.innerHTML = `
                <div class="formOrderCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Cập nhật trạng thái đơn hàng #${orderId}</div>
                        
                        <div class="wrapperInputCss">
                            <label for="status">Trạng thái:</label>
                            <select id="status" class="selectOrder">
                                <option value="pending">Chờ xử lý</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                        
                        <div class="wrapperButton">
                            <button class="buttonOrderCss" onclick="saveOrderStatus(this, '${orderId}')">Lưu thay đổi</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(portalRoot);
        }

        // Hàm lưu trạng thái đơn hàng
        function saveOrderStatus(button, orderId) {
            const status = document.getElementById('status').value;
            
            fetch('../../api/update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cập nhật trạng thái thành công');
                    closeForm();
                    fetchOrders(); // Tải lại danh sách đơn hàng
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }

        // Hàm đóng form
        function closeForm() {
            const portalRoot = document.getElementById('portal-root');
            if (portalRoot) {
                portalRoot.remove();
            }
        }

        // Tải danh sách đơn hàng khi trang được tải
        document.addEventListener('DOMContentLoaded', fetchOrders);

        // Xử lý tìm kiếm
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#orderTableBody tr');
            
            rows.forEach(row => {
                const orderId = row.cells[0].textContent.toLowerCase();
                if (orderId.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function showFormFilter() {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML=`
                <div class="formOrderCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Bộ lọc nâng cao</div>
                        
                        <label for="orderId">Mã đơn hàng:</label>
                        <div class="wrapperInputCss">
                            <input class="inputOrderCss" type="text" id="orderId" placeholder="Nhập mã đơn hàng">
                        </div>
                        
                        <label for="customerName">Tên khách hàng:</label>
                        <div class="wrapperInputCss">
                            <input class="inputOrderCss" type="text" id="customerName" placeholder="Nhập tên khách hàng">
                        </div>
                        
                        <label for="dateFrom">Ngày đặt từ:</label>
                        <input type="date" id="dateFrom" class="selectOrder">
                        
                        <label for="dateTo">Đến ngày:</label>
                        <input type="date" id="dateTo" class="selectOrder">
                        
                        <label for="statusFilter">Trạng thái:</label>
                        <select class="selectOrder" id="statusFilter">
                            <option value="">Tất cả</option>
                            <option value="pending">Chờ xử lý</option>
                            <option value="processing">Đang xử lý</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                        
                        <div class="wrapperButton">
                            <button class="buttonOrderCss" onclick="applyFilters()">Áp dụng bộ lọc</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);
        }

        function applyFilters() {
            const orderId = document.getElementById('orderId').value.trim();
            const customerName = document.getElementById('customerName').value.trim();
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const status = document.getElementById('statusFilter').value;
            
            // Here you would typically make an AJAX call to filter orders
            alert('Đã áp dụng bộ lọc: ' + 
                  `\nMã đơn: ${orderId || 'Tất cả'}` + 
                  `\nKhách hàng: ${customerName || 'Tất cả'}` + 
                  `\nTừ ngày: ${dateFrom || 'Không chọn'}` + 
                  `\nĐến ngày: ${dateTo || 'Không chọn'}` + 
                  `\nTrạng thái: ${status ? getStatusText(status) : 'Tất cả'}`);
            
            closeForm();
        }
    </script>
</body>
</html>