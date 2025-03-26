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
                    <tbody>
                        <tr>
                            <td>DH001</td>
                            <td>Lê Minh</td>
                            <td>15/03/2024</td>
                            <td>1,250,000đ</td>
                            <td>Chuyển khoản</td>
                            <td><span class="status processing">Đang xử lý</span></td>
                            <td>
                                <button class="btn btn-outline btn-sm" onclick="viewOrderDetails()">
                                    <i class="fas fa-eye"></i> Xem
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="updateOrderStatus(this)">
                                    <i class="fas fa-edit"></i> Cập nhật
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>DH002</td>
                            <td>Nguyễn Văn A</td>
                            <td>10/03/2024</td>
                            <td>2,500,000đ</td>
                            <td>Tiền mặt</td>
                            <td><span class="status completed">Hoàn thành</span></td>
                            <td>
                                <button class="btn btn-outline btn-sm" onclick="viewOrderDetails()">
                                    <i class="fas fa-eye"></i> Xem
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="updateOrderStatus(this)">
                                    <i class="fas fa-edit"></i> Cập nhật
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function viewOrderDetails() {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML=`
                <div class="formOrderCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Chi tiết đơn hàng #DH001</div>
                        <div><strong>Khách hàng:</strong> Lê Minh</div>
                        <div><strong>Ngày đặt:</strong> 15/03/2024</div>
                        <div><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM</div>
                        <div><strong>SĐT:</strong> 0837002323</div>
                        <div><strong>Phương thức thanh toán:</strong> Chuyển khoản</div>
                        <div><strong>Trạng thái:</strong> <span class="status processing">Đang xử lý</span></div>
                        
                        <div class="order-details">
                            <strong>Chi tiết sản phẩm:</strong>
                            <table class="order-items">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>iPhone 13 Pro Max</td>
                                        <td>1</td>
                                        <td>1,000,000đ</td>
                                        <td>1,000,000đ</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>AirPods Pro</td>
                                        <td>1</td>
                                        <td>250,000đ</td>
                                        <td>250,000đ</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="order-summary">
                                <div><strong>Tạm tính:</strong> 1,250,000đ</div>
                                <div><strong>Phí vận chuyển:</strong> 0đ</div>
                                <div><strong>Tổng cộng:</strong> 1,250,000đ</div>
                            </div>
                        </div>
                        <div class="wrapperButton">
                            <button class="buttonOrderCss" onclick="closeForm()">Đóng</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);
        }

        function updateOrderStatus(button) {
            const row = button.closest('tr');
            const currentStatus = row.children[5].querySelector('.status').className;
            
            let statusClass = '';
            if (currentStatus.includes('pending')) statusClass = 'pending';
            if (currentStatus.includes('processing')) statusClass = 'processing';
            if (currentStatus.includes('completed')) statusClass = 'completed';
            if (currentStatus.includes('cancelled')) statusClass = 'cancelled';

            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML=`
                <div class="formOrderCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeForm()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Cập nhật trạng thái đơn hàng #${row.children[0].innerText.trim()}</div>
                        <div><strong>Khách hàng:</strong> ${row.children[1].innerText.trim()}</div>
                        <div><strong>Ngày đặt:</strong> ${row.children[2].innerText.trim()}</div>
                        <div><strong>Tổng tiền:</strong> ${row.children[3].innerText.trim()}</div>
                        
                        <label for="status">Trạng thái:</label>
                        <select class="selectOrder" id="status">
                            <option value="pending" ${statusClass === 'pending' ? 'selected' : ''}>Chờ xử lý</option>
                            <option value="processing" ${statusClass === 'processing' ? 'selected' : ''}>Đang xử lý</option>
                            <option value="completed" ${statusClass === 'completed' ? 'selected' : ''}>Hoàn thành</option>
                            <option value="cancelled" ${statusClass === 'cancelled' ? 'selected' : ''}>Đã hủy</option>
                        </select>
                        
                        <label for="note">Ghi chú (nếu có):</label>
                        <div class="wrapperInputCss">
                            <textarea class="inputOrderCss" id="note" rows="3"></textarea>
                        </div>
                        
                        <div class="wrapperButton">
                            <button class="buttonOrderCss" onclick="saveOrderStatus(this, '${row.children[0].innerText.trim()}')">Lưu thay đổi</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);
        }

        function saveOrderStatus(button, orderId) {
            const status = document.getElementById('status').value;
            const note = document.getElementById('note').value.trim();
            
            // Here you would typically make an AJAX call to save the status
            alert(`Đã cập nhật trạng thái đơn hàng ${orderId} thành: ${getStatusText(status)}`);
            
            // Update the UI
            const row = document.querySelector(`td:contains(${orderId})`).closest('tr');
            const statusCell = row.children[5];
            
            let statusClass = '';
            let statusText = '';
            
            switch(status) {
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
            
            statusCell.innerHTML = `<span class="status ${statusClass}">${statusText}</span>`;
            
            closeForm();
        }

        function getStatusText(status) {
            switch(status) {
                case 'pending': return 'Chờ xử lý';
                case 'processing': return 'Đang xử lý';
                case 'completed': return 'Hoàn thành';
                case 'cancelled': return 'Đã hủy';
                default: return status;
            }
        }

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

        function closeForm() {
            const portalRoot = document.getElementById('portal-root');
            if (portalRoot) {
                portalRoot.remove();
            }
        }
    </script>
</body>
</html>