<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Phân Tích</title>
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

        .date-label {
            font-size: 14px;
            color: var(--gray);
            margin-right: 10px;
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
            height: 150px;
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 16px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .chart-placeholder:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .chart-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
            opacity: 0.8;
        }

        .chart-placeholder p {
            margin: 10px 0;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            color: var(--dark);
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }

        .chart-placeholder i {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        #revenueChart {
            background: linear-gradient(135deg, #e6f0fa 0%, #d0e3f5 100%);
        }

        #orderStatsChart {
            background: linear-gradient(135deg, #e9f7ef 0%, #d1f0e0 100%);
        }

        #userStats {
            background: linear-gradient(135deg, #f3e9fa 0%, #e3d3f2 100%);
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

        .sort-btn:hover, .sort-btn.active {
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
            cursor: pointer;
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

        .error-message {
            color: #dc2626;
            text-align: center;
            padding: 20px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 80%;
            max-width: 800px;
            position: relative;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }

        .close-btn {
            font-size: 24px;
            cursor: pointer;
            color: var(--gray);
            transition: var(--transition);
        }

        .close-btn:hover {
            color: var(--dark);
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .modal-table th,
        .modal-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .modal-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            font-size: 12px;
        }

        .modal-table td {
            color: var(--dark);
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

            .modal-content {
                width: 90%;
                margin: 10% auto;
            }

            .chart-placeholder {
                height: 120px;
                font-size: 14px;
            }

            .chart-placeholder p {
                font-size: 16px;
            }

            .chart-placeholder i {
                font-size: 20px;
            }
        }
    </style>    
</head>
<body>
    <div class="main-content">
        <div class="page-title">
            <div class="title">THỐNG KÊ DỮ LIỆU </div>
        </div>

        <!-- Thẻ Thống Kê -->
        <div class="stats-cards" id="statsCards">
            <!-- Dữ liệu sẽ được điền động -->
        </div>

        <!-- Thống Kê Khách Hàng Hàng Đầu -->
        <div class="customer-stats">
            <div class="chart-header">
                <div class="chart-title">Top 5 Khách Hàng Theo Giá Trị Mua Hàng</div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Khoảng Thời Gian</label>
                    <div class="date-range-group">
                        <span class="date-label">Từ</span>
                        <input type="date" class="date-input" id="customerStartDate">
                        <span class="date-separator">đến</span>
                        <input type="date" class="date-input" id="customerEndDate">
                        <button class="filter-btn" onclick="fetchTopCustomers()">Áp Dụng</button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sắp Xếp Theo</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc" onclick="fetchTopCustomers()">
                            <i class="fas fa-sort-amount-down"></i> Cao đến Thấp
                        </button>
                        <button class="sort-btn" data-sort="asc" onclick="fetchTopCustomers()">
                            <i class="fas fa-sort-amount-up"></i> Thấp đến Cao
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table" id="topCustomersTable">
                <thead>
                    <tr>
                        <th>Xếp Hạng</th>
                        <th>Tên Khách Hàng</th>
                        <th>Tổng Đơn Hàng</th>
                        <th>Tổng Giá Trị</th>
                        <th>Ngày Đặt Hàng Cuối</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu sẽ được điền động -->
                </tbody>
            </table>
        </div>

        <!-- Thống Kê Sản Phẩm Bán Chạy -->
        <div class="customer-stats">
            <div class="chart-header">
                <div class="chart-title">Sản Phẩm Bán Chạy Nhất</div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Khoảng Thời Gian</label>
                    <div class="date-range-group">
                        <span class="date-label">Từ</span>
                        <input type="date" class="date-input" id="productStartDate">
                        <span class="date-separator">đến</span>
                        <input type="date" class="date-input" id="productEndDate">
                        <button class="filter-btn" onclick="fetchTopProducts()">Áp Dụng</button>
                    </div>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">Sắp Xếp Theo</label>
                    <div class="sort-options">
                        <button class="sort-btn active" data-sort="desc" onclick="fetchTopProducts()">
                            <i class="fas fa-sort-amount-down"></i> Cao đến Thấp
                        </button>
                        <button class="sort-btn" data-sort="asc" onclick="fetchTopProducts()">
                            <i class="fas fa-sort-amount-up"></i> Thấp đến Cao
                        </button>
                    </div>
                </div>
            </div>

            <table class="customer-table" id="topProductsTable">
                <thead>
                    <tr>
                        <th>Xếp Hạng</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Danh Mục</th>
                        <th>Số Lượng Bán</th>
                        <th>Tổng Doanh Thu</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu sẽ được điền động -->
                </tbody>
            </table>
        </div>
        
        <!-- Biểu Đồ Doanh Thu -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Tổng Quan Doanh Thu</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <span class="date-label">Từ</span>
                        <input type="date" class="date-input" id="revenueStartDate">
                        <span class="date-separator">đến</span>
                        <input type="date" class="date-input" id="revenueEndDate">
                        <button class="filter-btn" onclick="fetchRevenue()">Áp Dụng</button>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="revenueChart">
                <i class="fas fa-dollar-sign"></i>
                <p>Biểu đồ doanh thu sẽ được hiển thị tại đây</p>
            </div>
        </div>

        <!-- Biểu Đồ Thống Kê Đơn Hàng -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Thống Kê Đơn Hàng</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <span class="date-label">Từ</span>
                        <input type="date" class="date-input" id="orderStartDate">
                        <span class="date-separator">đến</span>
                        <input type="date" class="date-input" id="orderEndDate">
                        <button class="filter-btn" onclick="fetchOrderStats()">Áp Dụng</button>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="orderStatsChart">
                <i class="fas fa-shopping-cart"></i>
                <p>Biểu đồ thống kê đơn hàng sẽ được hiển thị tại đây</p>
            </div>
        </div>

        <!-- Thống Kê Tài Khoản Người Dùng -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Thống Kê Tài Khoản Người Dùng</div>
                <div class="chart-filters">
                    <div class="date-range-group">
                        <span class="date-label">Từ</span>
                        <input type="date" class="date-input" id="userStartDate">
                        <span class="date-separator">đến</span>
                        <input type="date" class="date-input" id="userEndDate">
                        <button class="filter-btn" onclick="fetchActiveUsers()">Áp Dụng</button>
                    </div>
                </div>
            </div>
            <div class="chart-placeholder" id="userStats">
                <i class="fas fa-users"></i>
                <p>Thống kê người dùng sẽ được hiển thị tại đây</p>
            </div>
        </div>

        <!-- Modal cho Chi Tiết Đơn Hàng/Sản Phẩm -->
        <div id="detailsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle" class="modal-title"></h2>
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                </div>
                <div id="modalContent">
                    <!-- Nội dung động sẽ được điền tại đây -->
                </div>
            </div>
        </div>
    </div>

    <script src="../../JS/admin/analytics.js"></script>
</body>
</html>