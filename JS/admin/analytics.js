const ANALYTICS_API_URL = '../../src/router/AnalyticsRouter.php';

// Fetch and display stats cards
async function fetchStats() {
    try {
        const defaultStartDate = '2024-01-01';
        const defaultEndDate = new Date().toISOString().split('T')[0];
        const [revenueResponse, orderStatsResponse, activeUsersResponse] = await Promise.all([
            fetch(`${ANALYTICS_API_URL}?action=getTotalRevenue&startDate=${defaultStartDate}&endDate=${defaultEndDate}`),
            fetch(`${ANALYTICS_API_URL}?action=getOrderStats&startDate=${defaultStartDate}&endDate=${defaultEndDate}`),
            fetch(`${ANALYTICS_API_URL}?action=getActiveUsers&startDate=${defaultStartDate}&endDate=${defaultEndDate}`)
        ]);

        const revenueData = await revenueResponse.json();
        const orderStatsData = await orderStatsResponse.json();
        const activeUsersData = await activeUsersResponse.json();

        const statsCards = document.getElementById('statsCards');
        statsCards.innerHTML = `
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">$${revenueData.data?.total_revenue?.toFixed(2) || '0.00'}</div>
                        <div class="card-label">Total Revenue</div>
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% from last month</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">${orderStatsData.data['delivered'] || 0}</div>
                        <div class="card-label">Total Orders</div>
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>8.2% from last month</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">${activeUsersData.data?.active_users || 0}</div>
                        <div class="card-label">Active Users</div>
                    </div>
                    <div class="card-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>3.1% from last month</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">92%</div>
                        <div class="card-label">Conversion Rate</div>
                    </div>
                    <div class="card-icon orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="card-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>4.6% from last month</span>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Error fetching stats:', error);
        document.getElementById('statsCards').innerHTML = '<p>Error loading stats.</p>';
    }
}

async function fetchTopCustomers() {
    const startDate = document.getElementById('customerStartDate').value || '2024-01-01';
    const endDate = document.getElementById('customerEndDate').value || new Date().toISOString().split('T')[0];
    const sortBtn = document.querySelector('.sort-btn.active');
    const sort = sortBtn ? sortBtn.getAttribute('data-sort') : 'desc';

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getTopCustomers&startDate=${startDate}&endDate=${endDate}&limit=5`);
        const data = await response.json();

        if (data.status === 200) {
            const customers = data.data.sort((a, b) => {
                return sort === 'desc' ? b.total_purchase - a.total_purchase : a.total_purchase - b.total_purchase;
            }).slice(0, 5);

            const tbody = document.querySelector('#topCustomersTable tbody');
            tbody.innerHTML = customers.map((customer, index) => {
                const lastOrderDate = customer.orders && customer.orders.length > 0
                    ? customer.orders.reduce((latest, order) => 
                        latest.createdAt > order.createdAt ? latest : order
                    ).createdAt || 'N/A'
                    : 'N/A';

                return `
                    <tr>
                        <td><div class="customer-rank">${index + 1}</div></td>
                        <td>${customer.fullname || 'N/A'}</td>
                        <td>${customer.orders?.length || 0}</td>
                        <td class="total-amount">$${parseFloat(customer.total_purchase || 0).toFixed(2)}</td>
                        <td>${lastOrderDate}</td>
                        <td><a href="#" class="view-link" onclick="showCustomerOrders('${encodeURIComponent(customer.userID)}', '${encodeURIComponent(startDate)}', '${encodeURIComponent(endDate)}'); return false;">View Orders</a></td>
                    </tr>
                `;
            }).join('');
        } else {
            alert('Error fetching top customers: ' + data.data.error);
            document.querySelector('#topCustomersTable tbody').innerHTML = '<tr><td colspan="6">Error: ' + data.data.error + '</td></tr>';
        }
    } catch (error) {
        console.error('Error fetching top customers:', error);
        document.querySelector('#topCustomersTable tbody').innerHTML = '<tr><td colspan="6">Error loading data.</td></tr>';
    }
}

async function fetchTopProducts() {
    const startDate = document.getElementById('productStartDate').value || '2024-01-01';
    const endDate = document.getElementById('productEndDate').value || new Date().toISOString().split('T')[0];
    const sortBtn = document.querySelector('.sort-btn.active');
    const sort = sortBtn ? sortBtn.getAttribute('data-sort') : 'desc';

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getTopProducts&startDate=${startDate}&endDate=${endDate}&limit=5`);
        const data = await response.json();

        if (data.status === 200) {
            const products = data.data.sort((a, b) => {
                return sort === 'desc' ? parseFloat(b.total_revenue) - parseFloat(a.total_revenue) : parseFloat(a.total_revenue) - parseFloat(b.total_revenue);
            }).slice(0, 5);

            const tbody = document.querySelector('#topProductsTable tbody');
            tbody.innerHTML = products.map((product, index) => {
                return `
                    <tr>
                        <td><div class="customer-rank">${index + 1}</div></td>
                        <td>${product.name || 'N/A'}</td>
                        <td>${product.category || 'N/A'}</td>
                        <td>${product.total_quantity || 0}</td>
                        <td class="total-amount">$${parseFloat(product.total_revenue || 0).toFixed(2)}</td>
                        <td><a href="#" class="view-link" onclick="showProductDetails('${encodeURIComponent(product.productID)}', '${encodeURIComponent(startDate)}', '${encodeURIComponent(endDate)}'); return false;">View Details</a></td>
                    </tr>
                `;
            }).join('');
        } else {
            alert('Error fetching top products: ' + data.data.error);
            document.querySelector('#topProductsTable tbody').innerHTML = '<tr><td colspan="6">Error: ' + data.data.error + '</td></tr>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.querySelector('#topProductsTable tbody').innerHTML = '<tr><td colspan="6">Error loading data.</td></tr>';
    }
}

async function fetchRevenue() {
    const startDate = document.getElementById('revenueStartDate').value || '2024-01-01';
    const endDate = document.getElementById('revenueEndDate').value || new Date().toISOString().split('T')[0];
    const period = document.querySelector('.chart-filter.active')?.getAttribute('data-period') || 'daily';

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getTotalRevenue&startDate=${startDate}&endDate=${endDate}`);
        const data = await response.json();

        if (data.status === 200) {
            document.getElementById('revenueChart').innerHTML = `
                <p>Total Revenue: $${data.data?.total_revenue?.toFixed(2) || '0.00'} (${period.charAt(0).toUpperCase() + period.slice(1)})</p>
            `;
        } else {
            document.getElementById('revenueChart').innerHTML = '<p>Error loading revenue data.</p>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('revenueChart').innerHTML = '<p>Error loading revenue data.</p>';
    }
}

async function fetchOrderStats() {
    const startDate = document.getElementById('orderStartDate').value || '2024-01-01';
    const endDate = document.getElementById('orderEndDate').value || new Date().toISOString().split('T')[0];
    const period = document.querySelector('.chart-filter.active')?.getAttribute('data-period') || 'daily';

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getOrderStats&startDate=${startDate}&endDate=${endDate}`);
        const data = await response.json();

        if (data.status === 200) {
            const stats = data.data;
            document.getElementById('orderStatsChart').innerHTML = `
                <p>Total Orders: ${stats['delivered'] || 0} (Delivered) / ${stats['pending'] || 0} (Pending) (${period.charAt(0).toUpperCase() + period.slice(1)})</p>
            `;
        } else {
            document.getElementById('orderStatsChart').innerHTML = '<p>Error loading order stats.</p>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('orderStatsChart').innerHTML = '<p>Error loading order stats.</p>';
    }
}

async function fetchActiveUsers() {
    const startDate = document.getElementById('userStartDate').value || '2024-01-01';
    const endDate = document.getElementById('userEndDate').value || new Date().toISOString().split('T')[0];

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getActiveUsers&startDate=${startDate}&endDate=${endDate}`);
        const data = await response.json();

        if (data.status === 200) {
            document.getElementById('userStats').innerHTML = `
                <p>Active Users: ${data.data?.active_users || 0}</p>
            `;
        } else {
            document.getElementById('userStats').innerHTML = '<p>Error loading user stats.</p>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('userStats').innerHTML = '<p>Error loading user stats.</p>';
    }
}

// New function to show customer orders in modal
async function showCustomerOrders(userID, startDate, endDate) {
    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getCustomerOrderDetails&userID=${userID}&startDate=${startDate}&endDate=${endDate}`);
        const data = await response.json();

        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        modalTitle.textContent = 'Customer Order Details';

        if (data.status === 200 && data.data.length > 0) {
            modalContent.innerHTML = `
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.map(order => `
                            <tr>
                                <td>${order.orderID || 'N/A'}</td>
                                <td>${order.product_name || 'N/A'}</td>
                                <td>${order.quantity || 0}</td>
                                <td>$${parseFloat(order.price || 0).toFixed(2)}</td>
                                <td>$${parseFloat((order.quantity * order.price) || 0).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            modalContent.innerHTML = '<p>No order details available.</p>';
        }

        document.getElementById('detailsModal').style.display = 'block';
    } catch (error) {
        console.error('Error fetching customer orders:', error);
        document.getElementById('modalContent').innerHTML = '<p>Error loading order details.</p>';
        document.getElementById('detailsModal').style.display = 'block';
    }
}

// New function to show product details in modal
async function showProductDetails(productID, startDate, endDate) {
    try {
        const response = await fetch(`${ANALYTICS_API_URL}?action=getProductOrderDetails&productID=${productID}&startDate=${startDate}&endDate=${endDate}`);
        const data = await response.json();

        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        modalTitle.textContent = 'Product Order Details';

        if (data.status === 200 && data.data.length > 0) {
            modalContent.innerHTML = `
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.map(order => `
                            <tr>
                                <td>${order.orderID || 'N/A'}</td>
                                <td>${order.product_name || 'N/A'}</td>
                                <td>${order.quantity || 0}</td>
                                <td>$${parseFloat(order.price || 0).toFixed(2)}</td>
                                <td>$${parseFloat((order.quantity * order.price) || 0).toFixed(2)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            modalContent.innerHTML = '<p>No order details available.</p>';
        }

        document.getElementById('detailsModal').style.display = 'block';
    } catch (error) {
        console.error('Error fetching product details:', error);
        document.getElementById('modalContent').innerHTML = '<p>Error loading order details.</p>';
        document.getElementById('detailsModal').style.display = 'block';
    }
}

// Close modal
function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

document.querySelectorAll('.sort-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        const section = this.closest('.customer-stats')?.querySelector('.chart-title')?.textContent;
        if (section === 'Top 5 Customers by Purchase Amount') fetchTopCustomers();
        if (section === 'Most Bought Products') fetchTopProducts();
    });
});

document.querySelectorAll('.chart-filter').forEach(filter => {
    filter.addEventListener('click', function() {
        this.parentElement.querySelectorAll('.chart-filter').forEach(f => f.classList.remove('active'));
        this.classList.add('active');
        const container = this.closest('.chart-container')?.querySelector('.chart-title')?.textContent;
        if (container === 'Revenue Overview') fetchRevenue();
        if (container === 'Order Statistics') fetchOrderStats();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const defaultDate = new Date().toISOString().split('T')[0];
    document.getElementById('customerStartDate').value = '2024-01-01';
    document.getElementById('customerEndDate').value = defaultDate;
    document.getElementById('productStartDate').value = '2024-01-01';
    document.getElementById('productEndDate').value = defaultDate;
    document.getElementById('revenueStartDate').value = '2024-01-01';
    document.getElementById('revenueEndDate').value = defaultDate;
    document.getElementById('orderStartDate').value = '2024-01-01';
    document.getElementById('orderEndDate').value = defaultDate;
    document.getElementById('userStartDate').value = '2024-01-01';
    document.getElementById('userEndDate').value = defaultDate;

    fetchStats();
    fetchTopCustomers();
    fetchTopProducts();
    fetchRevenue();
    fetchOrderStats();
    fetchActiveUsers();

    // Add event listener to close modal when clicking outside
    document.getElementById('detailsModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });
});