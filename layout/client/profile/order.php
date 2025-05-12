<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/order.css">
</head>

<body>

<div class="order-history">
    <h2>Lịch sử mua hàng</h2>

    <table class="order-table">
    <thead>
        <tr>
        <th>Mã đơn</th>
        <th>Tổng tiền</th>
        <th>Ngày tạo</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
        </tr>
    </thead>
    
    </table>
</div>

<script src="/sportswear-webstore/JS/admin/order.js"></script>
<script>
   getOrdersByCustomer()
    .then(data => {
        displayOrders(data);
    })
    .catch(error => {
        console.error("Đã có lỗi xảy ra khi gọi API:", error);
    });

    function displayOrders(orders) {
        const tableBody = document.querySelector(".order-table");

        orders.forEach(order => {
            const row = document.createElement("tr");

            const orderIdCell = document.createElement("td");
            orderIdCell.textContent = `#${order.ID}`;
            row.appendChild(orderIdCell);

            const totalPriceCell = document.createElement("td");
            let finalPrice = order.totalPrice;

            if (order.couponPercent && !isNaN(order.couponPercent)) {
                finalPrice = finalPrice - (finalPrice * order.couponPercent / 100);
            }

            totalPriceCell.textContent = finalPrice.toLocaleString() + '₫';
            row.appendChild(totalPriceCell);

            const createdAtCell = document.createElement("td");
            const date = new Date(order.createdAt);
            createdAtCell.textContent = date.toLocaleString();
            row.appendChild(createdAtCell);

            const statusCell = document.createElement("td");
            const statusSpan = document.createElement("span");
            statusSpan.textContent = order.status === 'pending' ? 'Chờ duyệt' :
                                    order.status === 'approved' ? 'Đã duyệt' :
                                    order.status === 'canceled' ? 'Đã hủy' : 'Đã giao';
            statusCell.appendChild(statusSpan);
            row.appendChild(statusCell);

            const actionCell = document.createElement("td");
            const detailsButton = document.createElement("button");
            detailsButton.classList.add("details-btn");
            detailsButton.textContent = "Xem chi tiết";
            detailsButton.addEventListener("click", () => {
                viewOrderDetails(order.ID);
            });
            actionCell.appendChild(detailsButton);
            row.appendChild(actionCell);

            tableBody.appendChild(row);
        });
    }
    async function viewOrderDetails(orderId) {
        try {
            const data = await getOrderDetails(orderId);
            console.log('Chi tiết đơn hàng:', data);

            const orderList = data.data;
            const firstItem = orderList[0];

            const overlay = document.createElement('div');
            overlay.classList.add('popup-overlay');

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    document.body.removeChild(overlay);
                }
            });

            const popup = document.createElement('div');
            popup.classList.add('popup-content');

            let productsHTML = '';
            let totalAmount = 0;

            orderList.forEach(item => {
                productsHTML += `
                    <div class="invoice-items">
                        <p><strong>Sản phẩm:</strong> ${item.productName}</p>
                        <p><strong>Số lượng:</strong> ${item.quantity}</p>
                        <p><strong>Tổng tiền sản phẩm:</strong> ${Number(item.productTotal).toLocaleString()}₫</p>
                        <hr/>
                    </div>
                `;
                totalAmount += Number(item.productTotal);
            });

            const couponSection = firstItem.couponName ? `
                <p><strong>Mã giảm giá:</strong> ${firstItem.couponName}</p>
                <p><strong>Giảm giá:</strong> ${firstItem.couponPercent}%</p>
                <hr/>
            ` : '';

            let finalTotal = totalAmount;
            if (firstItem.couponPercent && firstItem.couponStatus === 'active') {
                finalTotal = totalAmount * (1 - firstItem.couponPercent / 100);
            }

            popup.innerHTML = `
                <div class="titlePopup">
                    <div>Chi Tiết Hóa Đơn</div>
                    <div class="close" style="cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="invoice-section">
                    <p><strong>Họ tên:</strong> ${firstItem.receiverName || '---'}</p>
                    <p><strong>Địa chỉ:</strong> ${firstItem.address || '---'}</p>
                    <p><strong>Số điện thoại:</strong> ${firstItem.phone || '---'}</p>
                    <p><strong>Hình thức thanh toán:</strong> ${firstItem.paymentMethod === 'online' ? 'Trực tuyến' : 'Tiền mặt'}</p>
                    <hr/>
                    ${productsHTML}
                    ${couponSection}
                    <p class="total-price"><strong>Tổng cộng:</strong> <span id="totalPrice">${finalTotal.toLocaleString()}₫</span></p>
                    <button class="btn-xong">Đóng</button>
                </div>
            `;

            overlay.appendChild(popup);
            document.body.appendChild(overlay);
        } catch (error) {
            console.error('Lỗi:', error.message);
        }
        document.querySelector('.close').addEventListener('click', () => {
        const overlay = document.querySelector('.popup-overlay');
        document.body.removeChild(overlay);
    });

    document.querySelector('.btn-xong').addEventListener('click', () => {
        const overlay = document.querySelector('.popup-overlay');
        document.body.removeChild(overlay);
    });
    }


</script>
</body>
</html>