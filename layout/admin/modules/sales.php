<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--secondary);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .filter-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--box-shadow);
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

        .filter-label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray);
            font-size: 14px;
        }

        .filter-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            outline: none;
            transition: var(--transition);
        }

        .filter-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        }

        .sales-table {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--gray);
            font-size: 13px;
            text-transform: uppercase;
        }

        .data-table tr:hover {
            background-color: #f8fafc;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status.completed {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status.pending {
            background-color: rgba(234, 179, 8, 0.1);
            color: #d97706;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        .status.cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .pagination-left {
            display: flex;
            gap: 10px;
        }

        .view-all-btn {
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .view-all-btn:hover {
            background: var(--secondary);
        }

        .view-all-btn.active {
            background: var(--secondary);
        }

        .page-numbers {
            display: flex;
            gap: 10px;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .page-btn:hover,
        .page-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Add style for when view all is active */
        .pagination.hide-numbers .page-numbers {
            display: none;
        }

        /* Add style for hidden rows */
        .data-table tr.hidden-row {
            display: none;
        }

        /* Add new styles for date range filter */
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

        .filter-btn {
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            height: 38px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn:hover {
            background: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="page-title">
            <div class="title">Sales Management</div>
            <div class="action-buttons">
                <button class="btn btn-outline">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-container">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <div class="date-range-group">
                        <input type="date" class="date-input" id="startDate">
                        <span class="date-separator">to</span>
                        <input type="date" class="date-input" id="endDate">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Product Category</label>
                    <select class="filter-input">
                        <option value="">All Categories</option>
                        <option value="shoes">Shoes</option>
                        <option value="clothing">Clothing</option>
                        <option value="accessories">Accessories</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-input">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group" style="flex: 0 0 auto;">
                    <label class="filter-label">&nbsp;</label>
                    <button class="filter-btn">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="sales-table">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-shopping-cart"></i>
                    Recent Sales
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#ORD-001</td>
                        <td>John Doe</td>
                        <td>$129.99</td>
                        <td>2024-03-15</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#ORD-002</td>
                        <td>Jane Smith</td>
                        <td>$45.99</td>
                        <td>2024-03-14</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#ORD-003</td>
                        <td>Mike Johnson</td>
                        <td>$35.99</td>
                        <td>2024-03-13</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-004</td>
                        <td>Sarah Wilson</td>
                        <td>$89.99</td>
                        <td>2024-03-12</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-005</td>
                        <td>David Brown</td>
                        <td>$65.99</td>
                        <td>2024-03-11</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-006</td>
                        <td>Emily Davis</td>
                        <td>$55.99</td>
                        <td>2024-03-10</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-007</td>
                        <td>James Wilson</td>
                        <td>$29.99</td>
                        <td>2024-03-09</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-008</td>
                        <td>Lisa Anderson</td>
                        <td>$39.99</td>
                        <td>2024-03-08</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-009</td>
                        <td>Robert Taylor</td>
                        <td>$79.99</td>
                        <td>2024-03-07</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-010</td>
                        <td>Maria Garcia</td>
                        <td>$25.99</td>
                        <td>2024-03-06</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-011</td>
                        <td>Thomas Lee</td>
                        <td>$45.99</td>
                        <td>2024-03-05</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-012</td>
                        <td>Jennifer Martinez</td>
                        <td>$35.99</td>
                        <td>2024-03-04</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-013</td>
                        <td>William Johnson</td>
                        <td>$99.99</td>
                        <td>2024-03-03</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-014</td>
                        <td>Patricia White</td>
                        <td>$49.99</td>
                        <td>2024-03-02</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                    <tr class="hidden-row">
                        <td>#ORD-015</td>
                        <td>Michael Brown</td>
                        <td>$29.99</td>
                        <td>2024-03-01</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <div class="pagination-left">
                    <button class="view-all-btn">
                        <i class="fas fa-list"></i> View All
                    </button>
                </div>
                <div class="page-numbers">
                    <button class="page-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal HTML at the end of the body, before the closing body tag -->
    <div id="orderModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="order-info">
                    <div class="info-group">
                        <label>Order ID:</label>
                        <span id="modalOrderId"></span>
                    </div>
                    <div class="info-group">
                        <label>Customer:</label>
                        <span id="modalCustomer"></span>
                    </div>
                    <div class="info-group">
                        <label>Date:</label>
                        <span id="modalDate"></span>
                    </div>
                    <div class="info-group">
                        <label>Status:</label>
                        <span id="modalStatus"></span>
                    </div>
                </div>
                <div class="order-items">
                    <h4>Order Items</h4>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modalItems">
                            <!-- Items will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="modalSubtotal"></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span id="modalShipping"></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="modalTotal"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const viewAllBtn = document.querySelector('.view-all-btn');
        const pageNumbers = document.querySelector('.page-numbers');
        const tableRows = document.querySelectorAll('.data-table tbody tr');

        function showRows(count) {
            tableRows.forEach((row, idx) => {
                if (idx < count) {
                    row.classList.remove('hidden-row');
                } else {
                    row.classList.add('hidden-row');
                }
            });
        }

        // Initial state: show 3 rows
        showRows(3);

        viewAllBtn.addEventListener('click', function() {
            const isViewAll = !this.classList.contains('active');
            if (isViewAll) {
                // Show all rows
                tableRows.forEach(row => row.classList.remove('hidden-row'));
                this.innerHTML = '<i class="fas fa-list"></i> View Less';
                this.classList.add('active');
                pageNumbers.style.display = 'none';
            } else {
                // Show only first 3 rows
                showRows(3);
                this.innerHTML = '<i class="fas fa-list"></i> View All';
                this.classList.remove('active');
                pageNumbers.style.display = 'flex';
            }
        });
    </script>
</body>
</html>