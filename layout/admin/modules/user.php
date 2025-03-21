<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
      <!--=============== REMIXICONS ===============-->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">


  <title>Dashboard</title>
    <style>

        .actionUser {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .search-box input {
        flex: 1;
        border: none;          /* Remove all borders */
        background-color: transparent; /* Make the background transparent */
        outline: none;         /* Remove outline when focused */
        color:#2d3748
        }
        .search-box {
        display: flex;
        align-items: center;
        justify-content:space-around;
        column-gap: .3rem;
        border: 1px solid #ccc;
        border-radius: 100px;
        height: 1.8rem;
        padding-left: 7px;
        max-width: 500px;
        width: 70%;
        padding: 20px;
        }
        .search-box:focus-within {
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.3);
    }
    .wrapperFilter{
        max-width: 500px;
        width: 70%;
        display: flex;
        gap: 20px;
    }
    </style>
</head>
<body>
<div class="stats-cards">
    <div class="table-card">
        <div class="card-title">
        <h3><i class="fa-solid fa-user"></i>Thông Tin Nhân Viên</h3>
        <div class="wrapperFilter">
            <div class="search-box" >
                <i class="ri-search-line"></i>
                <input type="text"placeholder="Tìm Kiếm Theo Số Điện Thoại">
            </div>
            <button class="btn btn-outline btn-sm">
                <i class="fa-solid fa-filter"></i>Bộ Lọc
            </button>
        </div>
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

