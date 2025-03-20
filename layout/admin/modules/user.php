<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
    <style>
        .actionUser {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>
<div class="stats-cards">
    <div class="table-card">
        <div class="card-title">
        <h3><i class="fa-solid fa-user"></i>Thông Tin Nhân Viên</h3>
        <button class="btn btn-outline btn-sm">
            <i class="fas fa-eye"></i> View All
        </button>
        </div>
        <table class="data-table">
        <thead>
            <tr>
            <th>User ID</th>
            <th>Họ Và Tên</th>
            <th>Ngày Sinh</th>
            <th>Email</th>
            <th>Số Điện Thoại</th>
            <th>Giới Tính</th>
            <th>Vai Trò</th>
            <th>Ngày Tạo</th>
            <th class="actionUser">Chức Năng</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#User-001</td>
                <td>Lê Minh</td>
                <td>14/03/2004</td>
                <td>minhleminh@gmail.com</td>
                <td>0837002323</td>
                <td>Nam</td>
                <td>Admin</td>
                <td>14/03/2024</td>
                <td >
                    <button class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i> Xem
                    </button>
                    <button class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-pen"></i> Sửa
                    </button>
                    <button class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-user-xmark"></i> Xóa
                    </button>
                </td>
                </td>
                </td>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</body>
</html>

