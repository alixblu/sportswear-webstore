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
            background-color: rgba(76, 201, 240, 0.15);
            color: #0891b2;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .status.inactive {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }

        .status.banned {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }

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

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }

        .wrapperFilter {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .role-selector-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        #roleSelector {
            width: 200px;
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
            color: #2d3748;
        }

        .search-box:focus-within {
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
            border-color: rgba(67, 97, 238, 0.3);
        }

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

        .formUserCss {
            background-color: white;
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            max-height: 90vh;
            overflow-y: auto;
        }

        .wrapperCss {
            padding: 0 30px;
            padding-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .genderCss {
            display: flex;
            margin: 10px 0;
            gap: 15px;
        }

        .inputUserCss {
            border: none;
            outline: none;
            color: #2d3748;
            font-size: 16px;
        }

        .wrapperInputCss {
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.1);
            transition: border-bottom 0.3s ease;
            border-bottom: 1px solid silver;
            padding: 8px 3px;
        }

        .wrapperInputCss:focus-within {
            border-bottom: 1px solid var(--primary);
        }

        .selectUser {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d1d1;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
            color: #2d3748;
            background-color: #fff;
            transition: var(--transition);
        }

        .selectUser:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .buttonUserCss {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .buttonUserCss:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            box-shadow: 0 6px 15px rgba(58, 12, 163, 0.2);
        }

        .wrapperButton {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .genderCss input[type="radio"] {
            margin-right: 8px;
        }

        .infoCss {
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 18px;
            color: var(--dark);
        }

        .deleteUserCss {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 10px;
        }

        .titleDeleteUserCss {
            margin: 10px;
            font-size: 17px;
        }

        #cancelDelete,
        #confirmDelete {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .wrapperFilterCss {
            background-color: white;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            box-shadow: var(--box-shadow);
            padding: 20px;
        }

        .wrapperFilterCss .wrapperCss {
            gap: 20px;
        }

        .wrapperFilterCss label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 5px;
            font-size: 14px;
        }

        .wrapperFilterCss .selectUser {
            border-color: #d1d1d1;
            padding: 10px;
        }

        .wrapperFilterCss .wrapperButton {
            margin-top: 10px;
        }

        .permissions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            padding: 15px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--dark);
        }

        .permission-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .permission-item input[type="checkbox"]:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .permission-item label {
            cursor: pointer;
            font-weight: 500;
        }

        #toast-portal {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 9999;
        }

        .toast {
            min-width: 250px;
            padding: 12px 18px;
            color: #fff;
            border-radius: 8px;
            font-size: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInOut 3s ease forwards;
        }

        .toast.success {
            background-color: #4caf50;
        }

        .toast.error {
            background-color: #f44336;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(20px);
            }
        }

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

            .permissions-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .wrapperFilterCss {
                max-width: 90%;
            }

            .formUserCss {
                max-width: 90%;
            }

            .role-selector-container {
                margin: 15px 0;
            }

            #roleSelector {
                width: 100%;
                max-width: 200px;
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

            .permission-item {
                font-size: 13px;
            }

            .wrapperFilterCss {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Quản lý tài khoản</div>
            <div class="action-buttons">
                <button id="exportBtn" class="btn btn-primary" onclick="exportData()">
                    <i class="fas fa-download"></i> Xuất dữ liệu
                </button>
                <button id="addBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm mới
                </button>
            </div>
        </div>
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('staff')">Tài khoản nhân viên</div>
            <div class="tab" onclick="switchTab('customer')">Tài khoản khách hàng</div>
            <div class="tab" onclick="switchTab('permissions')">Phân quyền</div>
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
                                <input type="text" placeholder="Tìm kiếm theo tên, số điện thoại..." oninput="searchAccounts('staff', this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('staff')">
                                <i class="fa-solid fa-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập (Email)</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="staff-tbody">
                            <!-- Data will be populated by JavaScript -->
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
                                <input type="text" placeholder="Tìm kiếm theo tên, số điện thoại..." oninput="searchAccounts('customer', this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('customer')">
                                <i class="fa-solid fa-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập (Email)</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng ký</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="customer-tbody">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div id="permissions-tab" class="tab-content">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-user-shield"></i> Quản lý phân quyền</h3>
                        <div class="wrapperFilter">
                            <button class="btn btn-primary" onclick="showCreateRoleForm()">
                                <i class="fas fa-plus"></i> Thêm vai trò mới
                            </button>
                        </div>
                    </div>
                    <div class="role-selector-container">
                        <select class="selectUser" id="roleSelector" onchange="loadPermissions()">
                            <!-- Roles will be populated by JavaScript -->
                        </select>
                    </div>
                    <div id="permissions-content" class="permissions-list">
                        <!-- Permissions will be populated by JavaScript -->
                    </div>
                    <div class="wrapperButton">
                        <button class="buttonUserCss" onclick="savePermissions()">
                            <i class="fas fa-save"></i> Lưu quyền hạn
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Portal -->
    <div id="portal-root" style="display: none;"></div>

    <!-- Toast Portal -->
    <div id="toast-portal"></div>

    <script src="../../JS/admin/account.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAll();
            switchTab('staff');
            loadRolesForPermissions();
        });
    </script>
</body>

</html>