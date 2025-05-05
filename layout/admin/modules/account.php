<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'sportswear');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query staff accounts (roleID != 5)
$sqlStaff = "SELECT 
                ua.ID AS accountID,
                ua.username,
                ua.password,
                u.fullname,
                u.phone,
                r.name AS roleName,
                ua.status,
                ua.createdAt,
                u.ID AS userID
            FROM 
                useraccount ua
            JOIN 
                user u ON ua.userID = u.ID
            JOIN 
                role r ON u.roleID = r.ID
            WHERE 
                u.roleID != 5";
$resultStaff = $conn->query($sqlStaff);

// Query customer accounts (roleID = 5)
$sqlCustomer = "SELECT 
                    ua.ID AS accountID,
                    ua.username,
                    ua.password,
                    u.fullname,
                    u.phone,
                    ua.status,
                    ua.createdAt,
                    u.ID AS userID
                FROM 
                    useraccount ua
                JOIN 
                    user u ON ua.userID = u.ID
                WHERE 
                    u.roleID = 5";
$resultCustomer = $conn->query($sqlCustomer);

// Fetch roles for the filter dropdown
$sqlRoles = "SELECT ID, name FROM role WHERE ID != 5";
$resultRoles = $conn->query($sqlRoles);
$roles = [];
while ($row = $resultRoles->fetch_assoc()) {
    $roles[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Remixicons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Quản lý tài khoản</title>
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

        /* Base styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            background-color: #f5f7fa;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
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

        /* Tab styles */
        .tab-container {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 15px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: var(--gray);
            transition: var(--transition);
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            font-weight: 600;
        }

        .tab-content {
            display: none;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            margin: 0 15px;
        }

        .tab-content.active {
            display: block;
        }

        /* Table styles */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .card-title {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title h3 {
            margin: 0;
            font-size: 18px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            font-size: 13px;
        }

        .data-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Status badges */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
            display: inline-block;
            min-width: 90px;
            text-align: center;
        }

        .status.active {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .status.inactive {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }

        .status.banned {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .actionUser {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
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

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }

        /* Search and filter */
        .wrapperFilter {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #ddd;
            border-radius: 50px;
            padding: 8px 15px;
            width: 100%;
            max-width: 400px;
            transition: var(--transition);
        }

        .search-box input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            padding: 0 10px;
            font-size: 14px;
        }

        .search-box:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        /* Password input */
        .wrapperInputCss.password-display {
            position: relative;
            display: flex;
            align-items: center;
        }

        .wrapperInputCss.password-display .inputUserCss {
            flex: 1;
            padding-right: 35px;
        }

        .wrapperInputCss.password-display .show-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: var(--gray);
            transition: var(--transition);
        }

        .wrapperInputCss.password-display .show-password:hover {
            color: var(--primary);
        }

        /* Modal styles */
        .formUserCss {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .CloseCss {
            text-align: right;
            margin-bottom: 10px;
        }

        .CloseCss i {
            cursor: pointer;
            font-size: 20px;
            color: var(--gray);
            transition: var(--transition);
        }

        .CloseCss i:hover {
            color: var(--danger);
        }

        .wrapperCss {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .infoCss {
            font-size: 1.3em;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .wrapperInputCss {
            margin-bottom: 15px;
            position: relative;
        }

        .inputUserCss,
        .selectUser {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
        }

        .inputUserCss:focus,
        .selectUser:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .genderCss {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }

        .genderCss input[type="radio"] {
            margin-right: 8px;
        }

        .buttonUserCss {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .buttonUserCss:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .wrapperButton {
            text-align: right;
            margin-top: 20px;
        }

        /* Permission styles */
        .permission-group {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: var(--border-radius);
        }

        .permission-group-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .permission-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
        }

        .permission-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .wrapperFilter {
                width: 100%;
            }

            .data-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .actionUser {
                flex-direction: column;
                gap: 5px;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .tab {
                padding: 8px 12px;
                font-size: 14px;
            }

            .data-table th,
            .data-table td {
                padding: 8px 10px;
                font-size: 12px;
            }

            .genderCss {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Quản lý tài khoản</div>
            <div class="action-buttons">
                <button id="exportBtn" class="btn btn-outline" onclick="exportData()">
                    <i class="fas fa-download"></i> Xuất dữ liệu
                </button>
            </div>
        </div>

        <div class="tab-container">
            <div class="tab active" onclick="switchTab('staff')">Tài khoản nhân viên</div>
            <div class="tab" onclick="switchTab('customer')">Tài khoản khách hàng</div>
        </div>

        <!-- Staff Accounts -->
        <div id="staff-tab" class="tab-content active">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-user-tie"></i> Thông tin tài khoản nhân viên</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="ri-search-line"></i>
                                <input type="text" placeholder="Tìm kiếm theo tên, số điện thoại..." oninput="searchStaff(this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('staff')">
                                <i class="fa-solid fa-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="actionUser">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultStaff->fetch_assoc()): ?>
                                <tr data-userid="<?php echo $row['userID']; ?>"
                                    data-accountid="<?php echo $row['accountID']; ?>"
                                    data-password="<?php echo $row['password']; ?>">
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['roleName']; ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower($row['status']); ?>">
                                            <?php
                                            switch (strtolower($row['status'])) {
                                                case 'active':
                                                    echo 'Hoạt động';
                                                    break;
                                                case 'inactive':
                                                    echo 'Không hoạt động';
                                                    break;
                                                case 'banned':
                                                    echo 'Bị cấm';
                                                    break;
                                                default:
                                                    echo $row['status'];
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($row['createdAt'])); ?></td>
                                    <td>
                                        <button class="btn btn-outline btn-sm" onclick="showPermissionsModal(this)">
                                            <i class="fas fa-user-shield"></i> Quyền hạn
                                        </button>
                                        <button class="btn btn-outline btn-sm" onclick="editStaffAccount(this)">
                                            <i class="fa-solid fa-pen"></i> Chỉnh sửa
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Accounts -->
        <div id="customer-tab" class="tab-content">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-users"></i> Thông tin tài khoản khách hàng</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="ri-search-line"></i>
                                <input type="text" placeholder="Tìm kiếm theo tên, số điện thoại..." oninput="searchCustomer(this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('customer')">
                                <i class="fa-solid fa-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng ký</th>
                                <th class="actionUser">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultCustomer->fetch_assoc()): ?>
                                <tr data-userid="<?php echo $row['userID']; ?>"
                                    data-accountid="<?php echo $row['accountID']; ?>"
                                    data-password="<?php echo $row['password']; ?>">
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower($row['status']); ?>">
                                            <?php
                                            switch (strtolower($row['status'])) {
                                                case 'active':
                                                    echo 'Hoạt động';
                                                    break;
                                                case 'inactive':
                                                    echo 'Không hoạt động';
                                                    break;
                                                case 'banned':
                                                    echo 'Bị cấm';
                                                    break;
                                                default:
                                                    echo $row['status'];
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($row['createdAt'])); ?></td>
                                    <td>
                                        <button class="btn btn-outline btn-sm" onclick="editCustomerAccount(this)">
                                            <i class="fa-solid fa-pen"></i> Chỉnh sửa
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Portal -->
    <div id="portal-root"></div>

    <!-- Hidden roles data for JavaScript -->
    <script id="roles-data" type="application/json">
        <?php echo json_encode($roles); ?>
    </script>

    <script>
        // Tab switching
        function switchTab(tabName) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // Export data
        function exportData() {
            alert('Đang xuất dữ liệu... (Cần tích hợp backend để tạo file CSV/XLSX)');
        }

        // Search staff
        function searchStaff(input) {
            const query = input.value.toLowerCase();
            const rows = document.querySelectorAll('#staff-tab tbody tr');
            rows.forEach(row => {
                const username = row.cells[0].innerText.toLowerCase();
                const fullname = row.cells[1].innerText.toLowerCase();
                const phone = row.cells[2].innerText.toLowerCase();
                row.style.display = (username.includes(query) || fullname.includes(query) || phone.includes(query)) ? '' : 'none';
            });
        }

        // Search customer
        function searchCustomer(input) {
            const query = input.value.toLowerCase();
            const rows = document.querySelectorAll('#customer-tab tbody tr');
            rows.forEach(row => {
                const username = row.cells[0].innerText.toLowerCase();
                const fullname = row.cells[1].innerText.toLowerCase();
                const phone = row.cells[2].innerText.toLowerCase();
                row.style.display = (username.includes(query) || fullname.includes(query) || phone.includes(query)) ? '' : 'none';
            });
        }

        // Show filter form
        function showFormFilter(type) {
            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Lọc ${type === 'staff' ? 'Tài khoản nhân viên' : 'Tài khoản khách hàng'}</div>
                        <label for="filter-status">Trạng thái</label>
                        <select class="selectUser" id="filter-status">
                            <option value="all">Tất cả</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                            <option value="banned">Bị cấm</option>
                        </select>
                        ${type === 'staff' ? `
                            <label for="filter-role">Vai trò</label>
                            <select class="selectUser" id="filter-role">
                                <option value="all">Tất cả</option>
                                ${JSON.parse(document.getElementById('roles-data').textContent).map(role => `
                                    <option value="${role.ID}">${role.name}</option>
                                `).join('')}
                            </select>
                        ` : ''}
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="applyFilter('${type}')">
                                <i class="fas fa-filter"></i> Áp dụng bộ lọc
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Apply filter
        function applyFilter(type) {
            const statusFilter = document.getElementById('filter-status').value;
            const roleFilter = type === 'staff' ? document.getElementById('filter-role').value : 'all';

            const rows = document.querySelectorAll(`#${type}-tab tbody tr`);
            rows.forEach(row => {
                const status = row.cells[type === 'staff' ? 4 : 3].innerText.toLowerCase();
                const role = type === 'staff' ? row.cells[3].innerText : '';

                const statusMatch = statusFilter === 'all' ||
                    (statusFilter === 'active' && status === 'hoạt động') ||
                    (statusFilter === 'inactive' && status === 'không hoạt động') ||
                    (statusFilter === 'banned' && status === 'bị cấm');

                const roleMatch = roleFilter === 'all' ||
                    (type === 'staff' && role === document.querySelector(`#filter-role option[value="${roleFilter}"]`).textContent);

                row.style.display = (statusMatch && roleMatch) ? '' : 'none';
            });
            closeModal();
        }

        // Toggle password visibility
        function togglePasswordInput(icon) {
            const input = icon.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Show permissions modal
        function showPermissionsModal(button) {
            const row = button.closest('tr');
            const userID = row.getAttribute('data-userid');
            const cells = row.querySelectorAll('td');
            const isAdmin = cells[3].innerText.trim() === 'Admin';

            const accountInfo = {
                username: cells[0].innerText,
                fullName: cells[1].innerText,
                role: cells[3].innerText
            };

            if (isAdmin) {
                alert('Tài khoản Admin có đầy đủ quyền hạn và không thể chỉnh sửa');
                return;
            }

            // Define permissions for modules
            const permissions = {
                "Dashboard": ["view", "export"],
                "Employees": ["view", "create", "edit", "delete"],
                "Products": ["view", "create", "edit", "delete"],
                "Warehouse": ["view", "create", "edit", "delete"],
                "Orders": ["view", "create", "edit", "cancel"],
                "Coupon & Discount": ["view", "create", "edit", "delete"],
                "Warranty": ["view", "create", "edit", "delete"],
                "Account & Access": ["view", "edit"],
                "Analytics": ["view", "export"],
                "Sales": ["view", "create", "edit"]
            };

            let permissionHTML = '';
            for (const [module, actions] of Object.entries(permissions)) {
                permissionHTML += `
                    <div class="permission-group">
                        <div class="permission-group-title">${module}</div>
                        <div class="permission-checkboxes">
                        ${actions.map(action => `
                            <div class="permission-option">
                                <input type="checkbox" id="perm-${module}-${action}" 
                                       ${Math.random() > 0.5 ? 'checked' : ''}>
                                <label for="perm-${module}-${action}">${action.charAt(0).toUpperCase() + action.slice(1)}</label>
                            </div>
                        `).join('')}
                        </div>
                    </div>
                `;
            }

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Quản lý quyền hạn</div>
                        <div><strong>Tài khoản:</strong> ${accountInfo.username}</div>
                        <div><strong>Họ tên:</strong> ${accountInfo.fullName}</div>
                        <div><strong>Vai trò hiện tại:</strong> ${accountInfo.role}</div>
                        ${permissionHTML}
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="savePermissions('${userID}')">
                                <i class="fas fa-save"></i> Lưu quyền hạn
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Edit staff account
        function editStaffAccount(button) {
            const row = button.closest('tr');
            const userID = row.getAttribute('data-userid');
            const accountID = row.getAttribute('data-accountid');
            const password = row.getAttribute('data-password');
            const cells = row.querySelectorAll('td');
            const isAdmin = cells[3].innerText.trim() === 'Admin';

            const accountInfo = {
                username: cells[0].innerText,
                password: password,
                fullName: cells[1].innerText,
                phone: cells[2].innerText,
                role: cells[3].innerText,
                status: cells[4].innerText.trim() === 'Hoạt động' ? 'active' : (cells[4].innerText.trim() === 'Không hoạt động' ? 'inactive' : 'banned'),
            };

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Chỉnh sửa tài khoản nhân viên</div>
                        <label for="staff-username">Tên đăng nhập</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-username" value="${accountInfo.username}" readonly>
                        </div>
                        <label for="staff-password">Mật khẩu</label>
                        <div class="wrapperInputCss password-display">
                            <input class="inputUserCss" type="password" id="staff-password" value="${accountInfo.password}">
                            <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                        </div>
                        <label for="staff-new-password">Mật khẩu mới (để trống nếu không đổi)</label>
                        <div class="wrapperInputCss password-display">
                            <input class="inputUserCss" type="password" id="staff-new-password" placeholder="Nhập mật khẩu mới">
                            <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                        </div>
                        <label for="staff-fullname">Họ và tên</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-fullname" value="${accountInfo.fullName}">
                        </div>
                        <label for="staff-phone">Số điện thoại</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="tel" id="staff-phone" value="${accountInfo.phone}">
                        </div>
                        ${!isAdmin ? `
                        <label for="staff-role">Vai trò</label>
                        <select class="selectUser" id="staff-role">
                            ${JSON.parse(document.getElementById('roles-data').textContent).map(role => `
                                <option value="${role.ID}" ${role.name === accountInfo.role ? 'selected' : ''}>${role.name}</option>
                            `).join('')}
                        </select>
                        ` : ''}
                        <label>Trạng thái</label>
                        <div class="genderCss">
                            <input type="radio" id="staff-active" name="staff-status" value="active" ${accountInfo.status === 'active' ? 'checked' : ''}>
                            <label for="staff-active">Hoạt động</label>
                            <input type="radio" id="staff-inactive" name="staff-status" value="inactive" ${accountInfo.status === 'inactive' ? 'checked' : ''}>
                            <label for="staff-inactive">Không hoạt động</label>
                            <input type="radio" id="staff-banned" name="staff-status" value="banned" ${accountInfo.status === 'banned' ? 'checked' : ''}>
                            <label for="staff-banned">Bị cấm</label>
                        </div>
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="saveStaffAccount('${userID}', '${accountID}', ${isAdmin})">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Edit customer account
        function editCustomerAccount(button) {
            const row = button.closest('tr');
            const userID = row.getAttribute('data-userid');
            const accountID = row.getAttribute('data-accountid');
            const password = row.getAttribute('data-password');
            const cells = row.querySelectorAll('td');

            const accountInfo = {
                username: cells[0].innerText,
                password: password,
                fullName: cells[1].innerText,
                phone: cells[2].innerText,
                status: cells[3].innerText.trim() === 'Hoạt động' ? 'active' : (cells[3].innerText.trim() === 'Không hoạt động' ? 'inactive' : 'banned'),
            };

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Chỉnh sửa tài khoản khách hàng</div>
                        <label for="customer-username">Tên đăng nhập</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-username" value="${accountInfo.username}" readonly>
                        </div>
                        <label for="customer-password">Mật khẩu</label>
                        <div class="wrapperInputCss password-display">
                            <input class="inputUserCss" type="password" id="customer-password" value="${accountInfo.password}">
                            <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                        </div>
                        <label for="customer-new-password">Mật khẩu mới (để trống nếu không đổi)</label>
                        <div class="wrapperInputCss password-display">
                            <input class="inputUserCss" type="password" id="customer-new-password" placeholder="Nhập mật khẩu mới">
                            <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                        </div>
                        <label for="customer-fullname">Họ và tên</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-fullname" value="${accountInfo.fullName}">
                        </div>
                        <label for="customer-phone">Số điện thoại</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="tel" id="customer-phone" value="${accountInfo.phone}">
                        </div>
                        <label>Trạng thái</label>
                        <div class="genderCss">
                            <input type="radio" id="customer-active" name="customer-status" value="active" ${accountInfo.status === 'active' ? 'checked' : ''}>
                            <label for="customer-active">Hoạt động</label>
                            <input type="radio" id="customer-inactive" name="customer-status" value="inactive" ${accountInfo.status === 'inactive' ? 'checked' : ''}>
                            <label for="customer-inactive">Không hoạt động</label>
                            <input type="radio" id="customer-banned" name="customer-status" value="banned" ${accountInfo.status === 'banned' ? 'checked' : ''}>
                            <label for="customer-banned">Bị cấm</label>
                        </div>
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="saveCustomerAccount('${userID}', '${accountID}')">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Close modal
        function closeModal() {
            document.getElementById('portal-root').style.display = 'none';
            document.getElementById('portal-root').innerHTML = '';
        }

        // Save permissions
        function savePermissions(userID) {
            const checkboxes = document.querySelectorAll('.permission-option input:checked');
            const selectedPermissions = Array.from(checkboxes).map(cb => cb.id);
            alert(`Đã lưu quyền hạn cho userID ${userID}: ${selectedPermissions.join(', ') || 'Không có quyền nào được chọn'}`);
            closeModal();
            // TODO: Send data to backend via API
        }

        // Save staff account
        function saveStaffAccount(userID, accountID, isAdmin) {
            const newPassword = document.getElementById('staff-new-password').value;
            const accountInfo = {
                username: document.getElementById('staff-username').value,
                password: newPassword ? newPassword : document.getElementById('staff-password').value,
                fullName: document.getElementById('staff-fullname').value,
                phone: document.getElementById('staff-phone').value,
                role: isAdmin ? 'Admin' : document.getElementById('staff-role').value,
                status: document.querySelector('input[name="staff-status"]:checked').value
            };

            if (!accountInfo.fullName || !accountInfo.phone) {
                alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
                return;
            }

            alert(`Đã lưu thông tin nhân viên cho userID ${userID}: ${JSON.stringify(accountInfo, null, 2)}`);
            closeModal();
            // TODO: Send data to backend via API
        }

        // Save customer account
        function saveCustomerAccount(userID, accountID) {
            const newPassword = document.getElementById('customer-new-password').value;
            const accountInfo = {
                username: document.getElementById('customer-username').value,
                password: newPassword ? newPassword : document.getElementById('customer-password').value,
                fullName: document.getElementById('customer-fullname').value,
                phone: document.getElementById('customer-phone').value,
                status: document.querySelector('input[name="customer-status"]:checked').value
            };

            if (!accountInfo.fullName || !accountInfo.phone) {
                alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
                return;
            }

            alert(`Đã lưu thông tin khách hàng cho userID ${userID}: ${JSON.stringify(accountInfo, null, 2)}`);
            closeModal();
            // TODO: Send data to backend via API
        }

        // Initialize with default tab
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('staff');
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>