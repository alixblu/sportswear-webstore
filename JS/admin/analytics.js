const ANALYTICS_API_URL = '../../src/router/AnalyticsRouter.php';

// Utility function to format numbers in Vietnamese style
function formatVND(amount) {
    return Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' ₫';
}

// Utility function to show toast notifications
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

// Fetch the earliest order date
async function fetchEarliestOrderDate() {
    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getEarliestOrderDate`);
        const data = await response.json();
        if (data.status === 200 && data.data.earliest_date) {
            return data.data.earliest_date.split(' ')[0]; // Extract YYYY-MM-DD
        }
        return '2024-01-01'; // Fallback date
    } catch (error) {
        console.error('Lỗi khi lấy ngày đặt hàng sớm nhất:', error);
        showToast('Không thể lấy ngày đặt hàng sớm nhất', 'error');
        return '2024-01-01';
    }
}

// Generic API fetch function
async function fetchAPI(endpoint, method = 'GET', body = null) {
    try {
        const options = {
            method,
            headers: { 'Content-Type': 'application/json' },
        };
        if (body) {
            options.body = JSON.stringify(body);
        }
        const response = await fetch(endpoint, options);
        const data = await response.json();
        if (!response.ok || data.error) {
            throw new Error(data.error || 'Yêu cầu thất bại');
        }
        return data;
    } catch (error) {
        console.error(`Lỗi API (${endpoint}):`, error);
        throw error;
    }
}

// Fetch and display stats cards
async function fetchStats() {
    try {
        const defaultStartDate = await fetchEarliestOrderDate();
        const defaultEndDate = new Date().toISOString().split('T')[0];
        const [revenueResponse, orderStatsResponse, activeUsersResponse] = await Promise.all([
            fetchAPI(`${ANALYTICS_API_URL}?action=getTotalRevenue&startDate=${defaultStartDate}&endDate=${defaultEndDate}`),
            fetchAPI(`${ANALYTICS_API_URL}?action=getOrderStats&startDate=${defaultStartDate}&endDate=${defaultEndDate}`),
            fetchAPI(`${ANALYTICS_API_URL}?action=getActiveUsers&startDate=${defaultStartDate}&endDate=${defaultEndDate}`)
        ]);

        const statsCards = document.getElementById('statsCards');
        statsCards.innerHTML = `
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">${formatVND(revenueResponse.data?.total_revenue || 0)}</div>
                        <div class="card-label">Tổng Doanh Thu</div>
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">${orderStatsResponse.data['delivered'] || 0}</div>
                        <div class="card-label">Tổng Đơn Hàng</div>
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">${activeUsersResponse.data?.active_users || 0}</div>
                        <div class="card-label">Người Dùng Hoạt Động</div>
                    </div>
                    <div class="card-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Lỗi khi lấy thống kê:', error);
        showToast('Không thể tải thống kê', 'error');
        document.getElementById('statsCards').innerHTML = '<p>Lỗi khi tải thống kê.</p>';
    }
}

// Fetch and display top customers
async function fetchTopCustomers() {
    const startDate = document.getElementById('customerStartDate').value || await fetchEarliestOrderDate();
    const endDate = document.getElementById('customerEndDate').value || new Date().toISOString().split('T')[0];
    const sortBtn = document.querySelector('.sort-btn.active');
    const sort = sortBtn ? sortBtn.getAttribute('data-sort') : 'desc';

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc', 'error');
        return;
    }

    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getTopCustomers&startDate=${startDate}&endDate=${endDate}&limit=5`);
        const customers = data.data.sort((a, b) => {
            return sort === 'desc' ? b.total_purchase - a.total_purchase : a.total_purchase - b.total_purchase;
        }).slice(0, 5);

        const tbody = document.querySelector('#topCustomersTable tbody');
        tbody.innerHTML = customers.map((customer, index) => {
            const lastOrderDate = customer.orders && customer.orders.length > 0
                ? customer.orders.reduce((latest, order) => 
                    latest.createdAt > order.createdAt ? latest : order
                ).createdAt || 'Không có'
                : 'Không có';

            return `
                <tr>
                    <td><div class="customer-rank">${index + 1}</div></td>
                    <td>${customer.fullname || 'Không có'}</td>
                    <td>${customer.orders?.length || 0}</td>
                    <td class="total-amount">${formatVND(customer.total_purchase || 0)}</td>
                    <td>${lastOrderDate}</td>
                    <td><a href="#" class="view-link" onclick="showCustomerOrders('${encodeURIComponent(customer.userID)}', '${encodeURIComponent(startDate)}', '${encodeURIComponent(endDate)}'); return false;">Xem Đơn Hàng</a></td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error('Lỗi khi lấy danh sách khách hàng hàng đầu:', error);
        showToast('Không thể tải danh sách khách hàng hàng đầu', 'error');
        document.querySelector('#topCustomersTable tbody').innerHTML = '<tr><td colspan="6">Lỗi khi tải dữ liệu.</td></tr>';
    }
}

// Fetch and display top products
async function fetchTopProducts() {
    const startDate = document.getElementById('productStartDate').value || await fetchEarliestOrderDate();
    const endDate = document.getElementById('productEndDate').value || new Date().toISOString().split('T')[0];
    const sortBtn = document.querySelector('.sort-btn.active');
    const sort = sortBtn ? sortBtn.getAttribute('data-sort') : 'desc';

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc', 'error');
        return;
    }

    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getTopProducts&startDate=${startDate}&endDate=${endDate}&limit=5`);
        const products = data.data.sort((a, b) => {
            return sort === 'desc' ? parseFloat(b.total_revenue) - parseFloat(a.total_revenue) : parseFloat(a.total_revenue) - parseFloat(b.total_revenue);
        }).slice(0, 5);

        const tbody = document.querySelector('#topProductsTable tbody');
        tbody.innerHTML = products.map((product, index) => {
            return `
                <tr>
                    <td><div class="customer-rank">${index + 1}</div></td>
                    <td>${product.name || 'Không có'}</td>
                    <td>${product.category || 'Không có'}</td>
                    <td>${product.total_quantity || 0}</td>
                    <td class="total-amount">${formatVND(product.total_revenue || 0)}</td>
                    <td><a href="#" class="view-link" onclick="showProductDetails('${encodeURIComponent(product.productID)}', '${encodeURIComponent(startDate)}', '${encodeURIComponent(endDate)}'); return false;">Xem Chi Tiết</a></td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error('Lỗi khi lấy danh sách sản phẩm hàng đầu:', error);
        showToast('Không thể tải danh sách sản phẩm hàng đầu', 'error');
        document.querySelector('#topProductsTable tbody').innerHTML = '<tr><td colspan="6">Lỗi khi tải dữ liệu.</td></tr>';
    }
}

// Fetch and display revenue
async function fetchRevenue() {
    const startDate = document.getElementById('revenueStartDate').value || await fetchEarliestOrderDate();
    const endDate = document.getElementById('revenueEndDate').value || new Date().toISOString().split('T')[0];

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc', 'error');
        return;
    }

    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getTotalRevenue&startDate=${startDate}&endDate=${endDate}`);
        document.getElementById('revenueChart').innerHTML = `
            <p>Tổng Doanh Thu: ${formatVND(data.data?.total_revenue || 0)}</p>
        `;
    } catch (error) {
        console.error('Lỗi khi lấy dữ liệu doanh thu:', error);
        showToast('Không thể tải dữ liệu doanh thu', 'error');
        document.getElementById('revenueChart').innerHTML = '<p>Lỗi khi tải dữ liệu doanh thu.</p>';
    }
}

// Fetch and display order statistics
async function fetchOrderStats() {
    const startDate = document.getElementById('orderStartDate').value || await fetchEarliestOrderDate();
    const endDate = document.getElementById('orderEndDate').value || new Date().toISOString().split('T')[0];

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc', 'error');
        return;
    }

    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getOrderStats&startDate=${startDate}&endDate=${endDate}`);
        const stats = data.data;
        document.getElementById('orderStatsChart').innerHTML = `
            <p>Tổng Đơn Hàng: ${stats['delivered'] || 0} (Đã Giao) / ${stats['pending'] || 0} (Đang Chờ)</p>
        `;
    } catch (error) {
        console.error('Lỗi khi lấy thống kê đơn hàng:', error);
        showToast('Không thể tải thống kê đơn hàng', 'error');
        document.getElementById('orderStatsChart').innerHTML = '<p>Lỗi khi tải thống kê đơn hàng.</p>';
    }
}

// Fetch and display active users
async function fetchActiveUsers() {
    const startDate = document.getElementById('userStartDate').value || await fetchEarliestOrderDate();
    const endDate = document.getElementById('userEndDate').value || new Date().toISOString().split('T')[0];

    if (!startDate || !endDate) {
        showToast('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc', 'error');
        return;
    }

    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getActiveUsers&startDate=${startDate}&endDate=${endDate}`);
        document.getElementById('userStats').innerHTML = `
            <p>Người Dùng Hoạt Động: ${data.data?.active_users || 0}</p>
        `;
    } catch (error) {
        console.error('Lỗi khi lấy thống kê người dùng:', error);
        showToast('Không thể tải thống kê người dùng', 'error');
        document.getElementById('userStats').innerHTML = '<p>Lỗi khi tải thống kê người dùng.</p>';
    }
}

// Show customer order details in modal
async function showCustomerOrders(userID, startDate, endDate) {
    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getCustomerOrderDetails&userID=${userID}&startDate=${startDate}&endDate=${endDate}`);
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        modalTitle.textContent = 'Chi Tiết Đơn Hàng Khách Hàng';

        if (data.status === 200 && data.data.length > 0) {
            modalContent.innerHTML = `
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.map(order => `
                            <tr>
                                <td>${order.orderID || 'Không có'}</td>
                                <td>${order.product_name || 'Không có'}</td>
                                <td>${order.quantity || 0}</td>
                                <td>${formatVND(order.price || 0)}</td>
                                <td>${formatVND((order.quantity * order.price) || 0)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            modalContent.innerHTML = '<p>Không có chi tiết đơn hàng.</p>';
        }

        document.getElementById('detailsModal').style.display = 'block';
    } catch (error) {
        console.error('Lỗi khi lấy chi tiết đơn hàng khách hàng:', error);
        showToast('Không thể tải chi tiết đơn hàng', 'error');
        document.getElementById('modalContent').innerHTML = '<p>Lỗi khi tải chi tiết đơn hàng.</p>';
        document.getElementById('detailsModal').style.display = 'block';
    }
}

// Show product order details in modal
async function showProductDetails(productID, startDate, endDate) {
    try {
        const data = await fetchAPI(`${ANALYTICS_API_URL}?action=getProductOrderDetails&productID=${productID}&startDate=${startDate}&endDate=${endDate}`);
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        modalTitle.textContent = 'Chi Tiết Đơn Hàng Sản Phẩm';

        if (data.status === 200 && data.data.length > 0) {
            modalContent.innerHTML = `
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.map(order => `
                            <tr>
                                <td>${order.orderID || 'Không có'}</td>
                                <td>${order.product_name || 'Không có'}</td>
                                <td>${order.quantity || 0}</td>
                                <td>${formatVND(order.price || 0)}</td>
                                <td>${formatVND((order.quantity * order.price) || 0)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            modalContent.innerHTML = '<p>Không có chi tiết đơn hàng.</p>';
        }

        document.getElementById('detailsModal').style.display = 'block';
    } catch (error) {
        console.error('Lỗi khi lấy chi tiết sản phẩm:', error);
        showToast('Không thể tải chi tiết đơn hàng', 'error');
        document.getElementById('modalContent').innerHTML = '<p>Lỗi khi tải chi tiết đơn hàng.</p>';
        document.getElementById('detailsModal').style.display = 'block';
    }
}

// Close modal
function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Event listeners for sort buttons
document.querySelectorAll('.sort-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        const section = this.closest('.customer-stats')?.querySelector('.chart-title')?.textContent;
        if (section === 'Top 5 Khách Hàng Theo Giá Trị Mua Hàng') fetchTopCustomers();
        if (section === 'Sản Phẩm Bán Chạy Nhất') fetchTopProducts();
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    const defaultStartDate = await fetchEarliestOrderDate();
    const defaultEndDate = new Date().toISOString().split('T')[0];

    // Set default dates for inputs
    document.getElementById('customerStartDate').value = defaultStartDate;
    document.getElementById('customerEndDate').value = defaultEndDate;
    document.getElementById('productStartDate').value = defaultStartDate;
    document.getElementById('productEndDate').value = defaultEndDate;
    document.getElementById('revenueStartDate').value = defaultStartDate;
    document.getElementById('revenueEndDate').value = defaultEndDate;
    document.getElementById('orderStartDate').value = defaultStartDate;
    document.getElementById('orderEndDate').value = defaultEndDate;
    document.getElementById('userStartDate').value = defaultStartDate;
    document.getElementById('userEndDate').value = defaultEndDate;

    // Fetch all data
    fetchStats();
    fetchTopCustomers();
    fetchTopProducts();
    fetchRevenue();
    fetchOrderStats();
    fetchActiveUsers();

    // Modal close event
    document.getElementById('detailsModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });
});