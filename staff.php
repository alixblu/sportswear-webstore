<?php
session_start();
include './src/config/exception/exceptionhandler.php';
include './src/controller/usercontroller.php';
$userController = new UserController();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/staff.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Quản lý tài khoản nhân viên</title>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h1>Admin <span>Panel</span></h1>
            </div>
            <nav class="nav-menu">
                <div class="menu-heading">Quản lý tài khoản</div>
                <a href="/staff-management" class="nav-item active">
                    <i class="ri-user-2-line"></i> Quản lý nhân viên
                </a>
                <a href="/customer-management" class="nav-item">
                    <i class="ri-user-3-line"></i> Quản lý khách hàng
                </a>
            </nav>
        </aside>

        <!-- Header -->
        <header class="header">
            <div class="search-bar">
                <i class="ri-search-line"></i>
                <input type="text" placeholder="Tìm kiếm nhân viên...">
            </div>
            <div class="header-actions">
                <div class="notification">
                    <i class="ri-notification-3-line"></i>
                    <span class="badge">3</span>
                </div>
                <div class="user-profile">
                    <div class="profile-img">AD</div>
                    <div class="user-info">
                        <span class="user-name">Admin</span>
                        <span class="user-role">Quản trị viên</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-title">
                <h2 class="title">Quản lý tài khoản nhân viên</h2>
                <div class="action-buttons">
                    <button class="btn btn-primary"><i class="ri-add-line"></i> Thêm mới</button>
                </div>
            </div>

            <!-- Bảng dữ liệu -->
            <div class="table-card">
                <div class="card-title">
                    <h3><i class="ri-table-line"></i> Danh sách nhân viên</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên tài khoản</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>nv001</td>
                            <td>0901234567</td>
                            <td><span class="status active"><i class="ri-checkbox-circle-line"></i> Hoạt động</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline"><i class="ri-edit-line"></i> Sửa</button>
                                <button class="btn btn-sm btn-outline"><i class="ri-delete-bin-line"></i> Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>nv002</td>
                            <td>0912345678</td>
                            <td><span class="status pending"><i class="ri-time-line"></i> Chờ duyệt</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline"><i class="ri-edit-line"></i> Sửa</button>
                                <button class="btn btn-sm btn-outline"><i class="ri-delete-bin-line"></i> Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>