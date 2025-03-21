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
        max-width: 400px;
        width: 70%;
        display: flex;
        gap: 20px;
    }

    .actionUser {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .formUserCss{
        background-color:white;
        max-width: 500px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .birthdayGenderCss{
        display: flex;
        gap: 100px;
    }
    .wrapperCss{
        padding: 0 30px;
        padding-bottom: 30px;
        display: flex;
        flex-direction: column;
        gap:10px
    }
    .genderCss{
        display: flex;
        margin: 10px 0px;
    }
    .inputUserCss{
        border: none;
        outline: none;
        color: #2d3748;
        font-size: 17px;
    }
    .wrapperInputCss{
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.1);
        transition: border-bottom 0.3s ease;
        border-bottom: 1px solid silver;
        padding: 5px 3px;
    }
    .wrapperInputCss:focus-within {
        border-bottom: 1px solid #00e5ff;
    }
    .wrapperBirthday{
        margin: 10px 0px;
    }
    .selectUser {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d1d1;
        border-radius: 6px;
        outline: none;
    }
    .buttonUserCss {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

    .buttonUserCss:hover {
        background-color: #45a049;
    }
    .wrapperButton{
        display: flex;
        gap: 10px
    }
    .CloseCss{
        display: flex;
        justify-content: flex-end;
        padding: 10px;
        cursor:pointer;
    }
    #portal-root {
        position: fixed;
        top: 0;
        left: 130px;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999; 
    }
    </style>
</head>
<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">User</div>
            <div class="action-buttons">
                <button id="importBtn" class="btn btn-outline">
                    <i class="fa-solid fa-upload"></i> Import
                </button>

                <button id="exportBtn" class="btn btn-outline">
                    <i class="fas fa-download"></i> Export
                </button>

                <button id="addBtn" class="btn btn-primary" onclick="showFormAddUser()">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>
        </div>
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
        </div>
    </div>
    <script>
        function showFormAddUser(){
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML=`
                <div class="formUserCss">
                    <div class="CloseCss" onclick="closeFormAddUser()"><i class="fa-solid fa-xmark"></i></div>
                    <div class="wrapperCss">
                        <label for="name">Họ và tên</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="name">
                        </div>
                        
                        <label for="email">Email</label>

                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="email">
                        </div>

                        
                        <label for="phone">Số điện thoại</label>
                        
                        <div class="wrapperInputCss">
                            <input type="text" class="inputUserCss" id="phone">
                        </div>
                        <div class="birthdayGenderCss">
                            <div >
                                <label for="birthday">Ngày Sinh</label><br>
                                <input class="wrapperBirthday" type="date" id="birthday">     
                            </div>
                            <div class="wrapperGender">
                                <label for="" >Giới Tính</label>
                                <div class="genderCss">
                                    <input type="radio" id="male" name="gender">
                                    <label for="male">Nam</label><br>
                                    <input type="radio" id="female" name="gender">
                                    <label for="female" >Nữ</label><br>
                                </div>
                            </div>
                        </div>
                        <label for="role">Vai Trò:</label>

                        <select class="selectUser" type="role" id="role">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                        <div>
                            <p>*Tài khoản nhân viên được thêm tự động,Tên Tài khoản là số điện thoại, mật khẩu: 123456</p>
                        </div>
                        <div class="wrapperButton">
                            <input class="buttonUserCss" type="submit" value="Thêm">
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);
        }
        function closeFormAddUser(){
            const portalRoot = document.getElementById('portal-root');
            if (portalRoot) {
                portalRoot.remove();
            }
        }
    </script>
</body>
</html>

