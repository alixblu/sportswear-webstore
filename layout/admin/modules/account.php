<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'sportswear');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn tài khoản nhân viên (roleID != 5)
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

// Truy vấn tài khoản khách hàng (roleID = 5)
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

// Lấy danh sách vai trò
$sqlRoles = "SELECT ID, name FROM role";
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
    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/web2/sportswear-webstore/css/admin/account.css">
    <title>Quản lý tài khoản</title>
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

        <!-- Tài khoản nhân viên -->
        <div id="staff-tab" class="tab-content active">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fas fa-users"></i> Danh sách tài khoản nhân viên</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Tìm kiếm..." oninput="searchStaff(this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('staff')">
                                <i class="fas fa-filter"></i> Bộ lọc
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
                            <?php while($row = $resultStaff->fetch_assoc()): ?>
                                <tr data-userid="<?php echo $row['userID']; ?>" data-accountid="<?php echo $row['accountID']; ?>" data-password="<?php echo htmlspecialchars($row['password']); ?>">
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['roleName']; ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower($row['status']); ?>">
                                            <?php 
                                            switch(strtolower($row['status'])) {
                                                case 'active': echo 'Hoạt động'; break;
                                                case 'inactive': echo 'Không hoạt động'; break;
                                                case 'banned': echo 'Bị cấm'; break;
                                                default: echo $row['status']; 
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

        <!-- Tài khoản khách hàng -->
        <div id="customer-tab" class="tab-content">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fas fa-users"></i> Danh sách tài khoản khách hàng</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Tìm kiếm..." oninput="searchCustomer(this)">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('customer')">
                                <i class="fas fa-filter"></i> Bộ lọc
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
                            <?php while($row = $resultCustomer->fetch_assoc()): ?>
                                <tr data-userid="<?php echo $row['userID']; ?>" data-accountid="<?php echo $row['accountID']; ?>" data-password="<?php echo htmlspecialchars($row['password']); ?>">
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower($row['status']); ?>">
                                            <?php 
                                            switch(strtolower($row['status'])) {
                                                case 'active': echo 'Hoạt động'; break;
                                                case 'inactive': echo 'Không hoạt động'; break;
                                                case 'banned': echo 'Bị cấm'; break;
                                                default: echo $row['status']; 
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

    <!-- Hidden data for JavaScript -->
    <script id="roles-data" type="application/json"><?php echo json_encode($roles); ?></script>

    <!-- JavaScript -->
    <script src="/web2/sportswear-webstore/JS/admin/account.js"></script>
</body>
</html>

<?php
$conn->close();
?>