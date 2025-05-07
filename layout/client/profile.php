<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <title>Client</title>
        <link rel="stylesheet" href="../../css/footer.css">
        <link rel="stylesheet" href="../../css/header.css">

        <!-- font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body{
                background-color: white;
            }
            main {
               padding-top: 90px; 
               width: 1160px;
               margin: 0 auto;
            }
            .wrapperContent {
                display: flex;
                gap: 40px;
                padding: 50px 80px;
            }

            /* Sidebar */
            .sidebar {
                width: 230px;
                display: flex;
                flex-direction: column;
                gap: 30px;
            }

            .section {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .section-title {
                font-weight: 600;
                font-size: 15px;
                color: #000;
                margin-bottom: 5px;
            }

            .section-items {
                display: flex;
                flex-direction: column;
                gap: 8px;
                padding-left: 16px;
            }

            .sidebar-item {
                font-size: 14px;
                color: #888;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .sidebar-item:hover {
                color: #000;
            }

            .sidebar-item.active {
                color: #d33b3b;
                font-weight: 500;
            }

            /* Profile Form */
            .profile-form {
                flex: 1;
                background-color: #fff;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.05);
            }

            .form-title {
                font-size: 20px;
                font-weight: 600;
                color: #d33b3b;
                margin-bottom: 30px;
            }

            .form-row {
                display: flex;
                gap: 20px;
                margin-bottom: 25px;
            }

            .form-group {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .form-group label {
                font-size: 13px;
                font-weight: 500;
                margin-bottom: 6px;
                color: #1f1f1f;
            }

            .form-group input {
                padding: 12px 14px;
                background-color: #f5f5f5;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                color: #333;
            }

            .form-group input:disabled {
                color: #999;
            }

            /* Password section */
            .form-section {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-bottom: 30px;
            }

            .form-section label {
                font-size: 14px;
                font-weight: 500;
                color: #1f1f1f;
                margin-bottom: 5px;
            }

            .form-section input {
                padding: 12px 14px;
                background-color: #f5f5f5;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                color: #333;
            }

            /* Button area */
            .form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 20px;
            }

            .save-btn {
                background-color: #d33b3b;
                color: white;
                border: none;
                padding: 12px 24px;
                font-size: 14px;
                font-weight: 500;
                border-radius: 6px;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }

            .save-btn:hover {
                background-color: #b82d2d;
            }
        </style>
    </head>

    <body>
     
    <?php include __DIR__ . '/../header.php'; ?>

        <main>
        <div class="wrapperContent">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="section">
                    <div class="section-title">Thông Tin Người Dùng</div>
                    <div class="section-items">
                        <div class="sidebar-item active">Tài Khoản</div>
                        <!-- <div class="sidebar-item">Address Book</div>
                        <div class="sidebar-item">My Payment Options</div> -->
                    </div>
                </div>
                <!-- <div class="section">
                    <div class="section-title">My Orders</div>
                    <div class="section-items">
                        <div class="sidebar-item">My Returns</div>
                        <div class="sidebar-item">My Cancellations</div>
                    </div>
                </div>
                <div class="section">
                    <div class="section-title">My WishList</div>
                </div> -->
            </div>

            <!-- Profile Form -->
            <div class="profile-form">
                <div class="form-title">Thông tin Tài Khoản</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">Họ Và Tên</label>
                        <input id="first-name" type="text" value="">
                    </div>
                    <!-- <div class="form-group">
                        <label for="last-name">Tên</label>
                        <input id="last-name" type="text" value="">
                    </div> -->
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" value="" disabled>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input id="address" type="text" value="">
                    </div>
                </div>

                <div class="form-section">
                    <label>Thay Đổi Mật Khẩu</label>
                    <input type="password" placeholder="Mật Khẩu">
                    <input type="password" placeholder="Mật Khẩu Mới">
                    <input type="password" placeholder="Xác Nhận Mật Khẩu Mới">
                </div>

                <div class="form-actions">
                    <button class="save-btn">Lưu Thay Đổi</button>
                </div>
            </div>
        </div>

        </main>
    </body>
    <script src="../../JS/admin/userApi.js"></script>
    <script>
        getInfo().then(data => {
            document.getElementById('first-name').value = data.data.fullname;
            document.getElementById('email').value = data.data.email;
            document.getElementById('address').value = data.data.address;
        }).catch(err => {
            console.error("Có lỗi xảy ra khi lấy thông tin:", err);
        });
    </script>
</html>