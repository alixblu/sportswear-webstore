<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        /* New styles for customer statistics */
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

        /* Responsive adjustments */
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
        <div class="stats-cards">
            <div class="stat-card">
                <div class="card-header">
                    <div>
                        <div class="card-value">$24,500</div>
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
                        <div class="card-value">1,234</div>
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
                        <div class="card-value">856</div>
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
                        <input type="date" class="date-input" id="startDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="endDate">
                        <button class="filter-btn">
                            Apply
                        </button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sort By</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc">
                            <i class="fas fa-sort-amount-down"></i> Highest to Lowest
                        </button>
                        <button class="sort-btn" data-sort="asc">
                            <i class="fas fa-sort-amount-up"></i> Lowest to Highest
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table">
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
                    <tr>
                        <td><div class="customer-rank">1</div></td>
                        <td>John Doe</td>
                        <td>15</td>
                        <td class="total-amount">$2,450.00</td>
                        <td>2024-03-15</td>
                        <td>
                            <a href="#" class="view-link">View Orders</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">2</div></td>
                        <td>Jane Smith</td>
                        <td>12</td>
                        <td class="total-amount">$1,890.00</td>
                        <td>2024-03-14</td>
                        <td>
                            <a href="#" class="view-link">View Orders</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">3</div></td>
                        <td>Mike Johnson</td>
                        <td>10</td>
                        <td class="total-amount">$1,560.00</td>
                        <td>2024-03-13</td>
                        <td>
                            <a href="#" class="view-link">View Orders</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">4</div></td>
                        <td>Sarah Wilson</td>
                        <td>8</td>
                        <td class="total-amount">$1,230.00</td>
                        <td>2024-03-12</td>
                        <td>
                            <a href="#" class="view-link">View Orders</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">5</div></td>
                        <td>David Brown</td>
                        <td>7</td>
                        <td class="total-amount">$980.00</td>
                        <td>2024-03-11</td>
                        <td>
                            <a href="#" class="view-link">View Orders</a>
                        </td>
                    </tr>
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
                        <button class="filter-btn">
                            Apply
                        </button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sort By</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc">
                            <i class="fas fa-sort-amount-down"></i> Highest to Lowest
                        </button>
                        <button class="sort-btn" data-sort="asc">
                            <i class="fas fa-sort-amount-up"></i> Lowest to Highest
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table">
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
                    <tr>
                        <td><div class="customer-rank">1</div></td>
                        <td>Nike Air Max 2024</td>
                        <td>Shoes</td>
                        <td>245</td>
                        <td class="total-amount">$36,750.00</td>
                        <td>
                            <a href="#" class="view-link">View Details</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">2</div></td>
                        <td>Adidas Ultraboost</td>
                        <td>Shoes</td>
                        <td>189</td>
                        <td class="total-amount">$28,350.00</td>
                        <td>
                            <a href="#" class="view-link">View Details</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">3</div></td>
                        <td>Under Armour Training Shorts</td>
                        <td>Clothing</td>
                        <td>156</td>
                        <td class="total-amount">$7,800.00</td>
                        <td>
                            <a href="#" class="view-link">View Details</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">4</div></td>
                        <td>Puma Running Jacket</td>
                        <td>Clothing</td>
                        <td>123</td>
                        <td class="total-amount">$12,300.00</td>
                        <td>
                            <a href="#" class="view-link">View Details</a>
                        </td>
                    </tr>
                    <tr>
                        <td><div class="customer-rank">5</div></td>
                        <td>New Balance Sports Cap</td>
                        <td>Accessories</td>
                        <td>98</td>
                        <td class="total-amount">$1,960.00</td>
                        <td>
                            <a href="#" class="view-link">View Details</a>
                        </td>
                    </tr>
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
                        <button class="filter-btn">Apply</button>
                    </div>
                    <div class="chart-period">
                        <div class="chart-filter active">Daily</div>
                        <div class="chart-filter">Weekly</div>
                        <div class="chart-filter">Monthly</div>
                        <div class="chart-filter">Yearly</div>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder">
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
                        <button class="filter-btn">Apply</button>
                    </div>
                    <div class="chart-period">
                        <div class="chart-filter active">Daily</div>
                        <div class="chart-filter">Weekly</div>
                        <div class="chart-filter">Monthly</div>
                        <div class="chart-filter">Yearly</div>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder">
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
                        <button class="filter-btn">Apply</button>
                    </div>
                </div>
            </div>
            <div class="user-stats-grid">
                <!-- ... existing user stats cards ... -->
            </div>
        </div>
    </div>

    <script>
        // Add JavaScript for sort functionality
        document.querySelectorAll('.sort-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Here you would typically sort the table data
                // For now, we're just toggling the UI
            });
        });

        // Add JavaScript for chart filters
        document.querySelectorAll('.chart-filter').forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters in the same group
                this.parentElement.querySelectorAll('.chart-filter').forEach(f => f.classList.remove('active'));
                // Add active class to clicked filter
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>