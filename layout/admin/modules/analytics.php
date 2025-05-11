<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* [Giữ nguyên CSS từ file cũ] */
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .main-content {
            padding: 20px;
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 15px;
        }

        .page-title .title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }

        .card-label {
            color: var(--gray);
            font-size: 14px;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .card-icon.purple {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .card-icon.blue {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .card-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .card-icon.orange {
            background: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }

        .card-change {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .card-change.positive {
            color: #059669;
        }

        .card-change.negative {
            color: #dc2626;
        }

        .chart-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        .chart-filters {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .date-range-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            outline: none;
            transition: var(--transition);
        }

        .date-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        }

        .date-separator {
            color: var(--gray);
        }

        .chart-period {
            display: flex;
            gap: 10px;
        }

        .chart-filter {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            color: var(--gray);
            transition: var(--transition);
        }

        .chart-filter:hover,
        .chart-filter.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .chart-placeholder {
            height: 300px;
            background: #f8fafc;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
        }

        .customer-stats {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
        }

        .sort-options {
            display: flex;
            gap: 10px;
        }

        .sort-btn {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            background: white;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .sort-btn:hover,
        .sort-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .customer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-table th,
        .customer-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .customer-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--gray);
            font-size: 13px;
            text-transform: uppercase;
        }

        .customer-table tr:hover {
            background-color: #f8fafc;
        }

        .view-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .total-amount {
            font-weight: 600;
            color: var(--dark);
        }

        .customer-rank {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .filter-btn {
            padding: 8px 16px;
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .filter-btn:hover {
            background: var(--primary);
            color: white;
        }

        @media (max-width: 768px) {
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .chart-filters {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .date-range-group {
                width: 100%;
                flex-wrap: wrap;
            }

            .date-input {
                flex: 1;
                min-width: 120px;
            }

            .filter-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="page-title">
            <div class="title">Analytics Dashboard</div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards" id="statsCards">
            <!-- Data will be populated dynamically -->
        </div>

        <!-- Top Customers Statistics -->
        <div class="customer-stats">
            <div class="chart-header">
                <div class="chart-title">Top 5 Customers by Purchase Amount</div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="customerStartDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="customerEndDate">
                        <button class="filter-btn" onclick="fetchTopCustomers()">Apply</button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sort By</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc" onclick="fetchTopCustomers()">
                            <i class="fas fa-sort-amount-down"></i> Highest to Lowest
                        </button>
                        <button class="sort-btn" data-sort="asc" onclick="fetchTopCustomers()">
                            <i class="fas fa-sort-amount-up"></i> Lowest to Highest
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table" id="topCustomersTable">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Customer Name</th>
                        <th>Total Orders</th>
                        <th>Total Amount</th>
                        <th>Last Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Most Bought Products Statistics -->
        <div class="customer-stats">
            <div class="chart-header">
                <div class="chart-title">Most Bought Products</div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="productStartDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="productEndDate">
                        <button class="filter-btn" onclick="fetchTopProducts()">Apply</button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sort By</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc" onclick="fetchTopProducts()">
                            <i class="fas fa-sort-amount-down"></i> Highest to Lowest
                        </button>
                        <button class="sort-btn" data-sort="asc" onclick="fetchTopProducts()">
                            <i class="fas fa-sort-amount-up"></i> Lowest to Highest
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table" id="topProductsTable">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Units Sold</th>
                        <th>Total Revenue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
        
        <!-- Revenue Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Revenue Overview</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="revenueStartDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="revenueEndDate">
                        <button class="filter-btn" onclick="fetchRevenue()">Apply</button>
                    </div>
                    <div class="chart-period">
                        <div class="chart-filter active" data-period="daily" onclick="fetchRevenue()">Daily</div>
                        <div class="chart-filter" data-period="weekly" onclick="fetchRevenue()">Weekly</div>
                        <div class="chart-filter" data-period="monthly" onclick="fetchRevenue()">Monthly</div>
                        <div class="chart-filter" data-period="yearly" onclick="fetchRevenue()">Yearly</div>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="revenueChart">
                Revenue Chart will be displayed here
            </div>
        </div>
        <!-- Order Statistics Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Order Statistics</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="orderStartDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="orderEndDate">
                        <button class="filter-btn" onclick="fetchOrderStats()">Apply</button>
                    </div>
                    <div class="chart-period">
                        <div class="chart-filter active" data-period="daily" onclick="fetchOrderStats()">Daily</div>
                        <div class="chart-filter" data-period="weekly" onclick="fetchOrderStats()">Weekly</div>
                        <div class="chart-filter" data-period="monthly" onclick="fetchOrderStats()">Monthly</div>
                        <div class="chart-filter" data-period="yearly" onclick="fetchOrderStats()">Yearly</div>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="orderStatsChart">
                Order Statistics Chart will be displayed here
            </div>
        </div>

        <!-- User Account Statistics -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">User Account Statistics</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="userStartDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="userEndDate">
                        <button class="filter-btn" onclick="fetchActiveUsers()">Apply</button>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="userStats">
                User Statistics will be displayed here
            </div>
        </div>
    </div>

    <script src="../../JS/admin/analytics.js"></script>
</body>
</html>