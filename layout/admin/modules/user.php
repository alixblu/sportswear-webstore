<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">


    <title>Dashboard</title>
    <style>
        .search-box input {
            flex: 1;
            border: none;
            /* Remove all borders */
            background-color: transparent;
            /* Make the background transparent */
            outline: none;
            /* Remove outline when focused */
            color: #2d3748
        }

        .search-box {
            display: flex;
            align-items: center;
            justify-content: space-around;
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

        .wrapperFilter {
            max-width: 400px;
            width: 70%;
            display: flex;
            gap: 20px;
        }


        .formUserCss {
            background-color: white;
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .birthdayGenderCss {
            display: flex;
            gap: 100px;
        }

        .wrapperCss {
            padding: 0 30px;
            padding-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .genderCss {
            display: flex;
            margin: 10px 0px;
            gap: 5px
        }

        .inputUserCss {
            border: none;
            outline: none;
            color: #2d3748;
            font-size: 17px;
        }

        .wrapperInputCss {
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

        .wrapperBirthday {
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
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .buttonUserCss:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            box-shadow: 0 6px 15px rgba(58, 12, 163, 0.2);
        }

        .wrapperButton {
            display: flex;
            gap: 10px
        }

        .CloseCss {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
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

        .infoCss {
            margin-bottom: 15px;
            font-weight: bold;
        }

        .status.active {
            background-color: rgba(76, 201, 240, 0.15);
            color: #0891b2;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .deleteUserCss {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 10px;
        }

        .titleDeleteUserCss {
            margin: 10px;
            font-size: 17px
        }

        #cancelDelete {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

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
            max-width: 500px;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            padding-left: 10px;
            padding-bottom: 10px;
            padding-right: 10px;
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
    </style>
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">User</div>
            <div class="action-buttons">
                <button id="importBtn" class="btn btn-outline" onclick="uploadFile()">
                    <i class="fa-solid fa-upload"></i> Import
                </button>
                <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;" onchange="handleFileChange(event)">
                <button id="exportBtn" class="btn btn-outline" onclick="exportFile()">
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
                        <div class="search-box">
                            <i class="ri-search-line"></i>
                            <input id="searchPhone" type="text" placeholder="Tìm Kiếm Theo Số Điện Thoại">
                        </div>
                        <button class="btn btn-outline btn-sm" onclick="showFormFilter()">
                            <i class="fa-solid fa-filter"></i>Bộ Lọc
                        </button>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ Và Tên</th>
                            <th>Ngày Sinh</th>
                            <th>Email</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Giới Tính</th>
                            <th>Vai Trò</th>
                            <th>Ngày Tạo</th>
                            <th>Chức Năng</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../../JS/admin/userApi.js"></script>
    <script>
        async function showAllUsers() {
            getAllUsers()
                .then(result => {
                    let stt = 1;
                    const users = result.data;
                    const tbody = document.querySelector(".data-table tbody");
                    tbody.innerHTML = "";
                    users.forEach(user => {
                        if (user.roleName == 'Customer') {
                            return;
                        }
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                        <td>${stt}</td>
                        <td>${user.fullname}</td>
                        <td>${user.dateOfBirth == undefined ? '':user.dateOfBirth}</td>
                        <td>${user.email == undefined ? '':user.email}</td>
                        <td>${user.phone}</td>
                        <td>${user.address  == undefined ? '':user.address}</td>
                        <td>${user.gender == 0 ? 'Nam' : 'Nữ'}</td>
                        <td>${user.roleName}</td>
                        <td>${user.createdAt}</td>
                        <td>
                            <button class="btn btn-outline btn-sm" onclick="infoAccount(${user.id})">
                                <i class="fas fa-eye"></i> Xem
                            </button>
                            <button class="btn btn-outline btn-sm" onclick="showFormEditUser(this, ${user.id})">
                                <i class="fa-solid fa-pen"></i> Sửa
                            </button>
                            <button class="btn btn-outline btn-sm" onclick="deleteUser(${user.id})">
                                <i class="fa-solid fa-user-xmark"></i> Xóa
                            </button>
                        </td>
                    `;

                        tbody.appendChild(tr);
                        stt = stt + 1
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách người dùng:', error.message);
                });
        }

        showAllUsers()

        function showFormAddUser() {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss" ><i class="fa-solid fa-xmark" onclick="closeFormAddUser()" style="cursor: pointer;"></i></div>
                    <div class="wrapperCss">
                        <label for="name">Họ và tên</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="name">
                        </div>
                        
                        <label for="email">Email</label>

                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="email">
                        </div>

                        <label for="address">Địa Chỉ</label>

                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="address">
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
                        </select>
                        <div>
                            <p>*Tài khoản nhân viên được thêm tự động,Tên Tài khoản là Email, mật khẩu: 123456</p>
                        </div>
                        <div class="wrapperButton">
                            <input class="buttonUserCss" type="submit" value="Thêm Nhân Viên" onclick="addUser()">
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);

            try {
                const select = portalRoot.querySelector('#role');
                getAllRoles()
                    .then(result => {
                        const roles = result.data;

                        roles.forEach(role => {
                            if (role.name == 'Customer') {
                                return;
                            }
                            const option = document.createElement('option');
                            option.value = role.id;
                            option.textContent = role.name;
                            select.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Không thể load roles:', error);
                    });
            } catch (error) {
                console.error('Lỗi xảy ra:', error);
            }
        }

        function closeFormAddUser() {
            const portalRoot = document.getElementById('portal-root');
            if (portalRoot) {
                portalRoot.remove();
            }
        }

        function addUser() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const address = document.getElementById('address').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const birthday = document.getElementById('birthday').value.trim();
            const genderEl = document.querySelector('input[name="gender"]:checked');
            let genderValue = null;
            if (genderEl) {
                genderValue = genderEl.value === 'nam' ? 0 : 1;
            } else {
                console.log('Chưa chọn giới tính');
            }
            const role = document.getElementById('role').value;


            if (!name || !email || !phone || !birthday || !genderValue || !role || !address) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }

            if (!validateEmail(email)) {
                alert('Email không hợp lệ.');
                return;
            }

            if (!/^\d{10}$/.test(phone)) {
                alert('Số điện thoại phải gồm 10 chữ số.');
                return;
            }
            createDefaultAccount(name, birthday, address, email, phone, genderValue, role)
                .then(response => {
                    if (response.status === 200) {
                        showToast('Thêm người dùng thành công!', 'success');
                        closeFormAddUser();
                        showAllUsers();
                    } else {
                        showToast(response.data, 'error');
                    }
                })
                .catch(error => {
                    showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
                });

        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showFormEditUser(button, id) {
            const row = button.closest('tr');
            const userInfo = {
                userId: row.children[0].innerText.trim(),
                fullName: row.children[1].innerText.trim(),
                birthDate: row.children[2].innerText.trim(),
                address: row.children[5].innerText.trim(),
                phone: row.children[4].innerText.trim(),
                gender: row.children[6].innerText.trim(),
                role: row.children[7].innerText.trim(),
            };

            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';

            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeFormAddUser()" style="cursor: pointer;"></i></div>
                    <div class="wrapperCss">
                        <label for="name">Họ và tên</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="name" value="${userInfo.fullName}">
                        </div>
                        
                        <label for="address">Địa Chỉ</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="address" value="${userInfo.address}">
                        </div>

                        <label for="phone">Số điện thoại</label>
                        <div class="wrapperInputCss">
                            <input type="text" class="inputUserCss" id="phone" value="${userInfo.phone}">
                        </div>

                        <div class="birthdayGenderCss">
                            <div>
                                <label for="birthday">Ngày Sinh</label><br>
                                <input class="wrapperBirthday" type="date" id="birthday" value="${userInfo.birthDate}">     
                            </div>
                            <div class="wrapperGender">
                                <label for="">Giới Tính</label>
                                <div class="genderCss">
                                    <input type="radio" id="male" name="gender" ${userInfo.gender === 'Nam' ? 'checked' : ''}>
                                    <label for="male">Nam</label><br>
                                    <input type="radio" id="female" name="gender" ${userInfo.gender === 'Nữ' ? 'checked' : ''}>
                                    <label for="female">Nữ</label><br>
                                </div>
                            </div>
                        </div>

                        <label for="role">Vai Trò:</label>
                        <select class="selectUser" id="role"></select>

                        <div>
                            <p>*Tài khoản nhân viên được thêm tự động, Tên Tài khoản là số điện thoại, mật khẩu: 123456</p>
                        </div>
                        <div class="wrapperButton">
                            <input class="buttonUserCss" type="submit" value="Sửa Thông Tin" onclick="editUser(${id})">
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(portalRoot);

            try {
                const select = portalRoot.querySelector('#role');
                getAllRoles()
                    .then(result => {
                        const roles = result.data;

                        roles.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.id;
                            option.textContent = role.name;

                            if (userInfo.role.toLowerCase() === role.name.toLowerCase()) {
                                option.selected = true;
                            }

                            select.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Không thể load roles:', error);
                    });
            } catch (error) {
                console.error('Lỗi xảy ra:', error);
            }

        }

        function formatDateForInput(dateString) {
            const parts = dateString.split('/');
            if (parts.length === 3) {
                const [day, month, year] = parts;
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }
            return '';
        }

        function editUser(id) {
            const name = document.getElementById('name').value.trim();
            const address = document.getElementById('address').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const birthday = document.getElementById('birthday').value.trim();
            const genderEl = document.querySelector('input[name="gender"]:checked');
            let genderValue = null;
            if (genderEl) {
                genderValue = genderEl.value === 'nam' ? 0 : 1;
            } else {
                console.log('Chưa chọn giới tính');
            }
            const role = document.getElementById('role').value;

            if (!name || !phone || !birthday || !genderEl || !role) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }

            if (!/^\d{10}$/.test(phone)) {
                alert('Số điện thoại phải gồm 10 chữ số.');
                return;
            }

            const gender = genderEl.value;

            updateUser(id, name, address, phone, genderValue, role)
                .then(response => {
                    if (response.status === 200) {
                        showToast('Cập nhật người dùng thành công!', 'success');
                        closeFormAddUser();
                        showAllUsers();
                    } else {
                        showToast(response.data, 'error');
                    }
                })
                .catch(error => {
                    showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
                });


        }

        function exportFile() {
            exportFileUser()
            showToast('export thành công!', 'success');
        }

        function uploadFile() {
            document.getElementById('fileInput').click()
        }

        function handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            uploadFileUser(file)
                .then(response => {
                    if (response.status === 200) {
                        showToast('import thành công!', 'success');
                    } else {
                        showToast('Email Đã Tồn Tại Không Được Thêm Vào \n' + response.data, 'error');
                    }
                })
                .catch(error => {
                    showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
                });
        }

        function infoAccount(id) {
            getAccountByUserId(id).then(result => {
                    const user = result.data;
                    const portalRoot = document.createElement('div');
                    portalRoot.id = 'portal-root';
                    portalRoot.innerHTML = `
                    <div class="formUserCss">
                        <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeFormAddUser()"></i></div>
                        <div class="wrapperCss">
                            <div class="infoCss">Thông Tin Tài Khoản</div>
                            <div>Tài Khoản: ${user.username}</div>
                            <div>Mật Khẩu: *********</div>
                            <div>Trạng Thái: <span class="status active"><i class="fas fa-check-circle"></i>Hoạt Động</span> </div>
                        </div>
                    </div>
                `;
                    document.body.appendChild(portalRoot);
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách người dùng:', error.message);
                });

        }

        function deleteUser(id) {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="titleDeleteUserCss">Bạn có chắc chắn muốn xóa không?</div>
                    <div class="deleteUserCss">
                        <button id="cancelDelete">Hủy</button>
                        <button id="confirmDelete">Xác nhận</button>
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);


            document.getElementById('confirmDelete').addEventListener('click', function() {
                deleteUserApi(id)
                    .then(response => {
                        if (response.status === 200) {
                            showToast('Xóa người dùng thành công!', 'success');
                            closeFormAddUser();
                            showAllUsers();
                        } else {
                            showToast(response.data, 'error');
                            closeFormAddUser();
                        }
                    })
                    .catch(error => {
                        showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
                        closeFormAddUser();
                    });
                showAllUsers();
            });

            document.getElementById('cancelDelete').addEventListener('click', function() {
                closeFormAddUser();
            });
        }

        function showToast(text, type = 'success') {
            let portalRoot = document.getElementById('toast-portal');

            if (!portalRoot) {
                portalRoot = document.createElement('div');
                portalRoot.id = 'toast-portal';
                document.body.appendChild(portalRoot);
            }

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerText = text;

            portalRoot.appendChild(toast);

            setTimeout(() => {
                toast.remove();
                if (portalRoot.children.length === 0) {
                    portalRoot.remove();
                }
            }, 3000);
        }


        function showFormFilter() {
            const portalRoot = document.createElement('div');
            portalRoot.id = 'portal-root';
            portalRoot.innerHTML = `
                <div class="wrapperFilterCss">
                    <div class="CloseCss">
                        <i class="fa-solid fa-xmark" onclick="closeFormAddUser()"></i>
                    </div>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="search" placeholder="Nội Dung Tìm Kiếm">
                    </div>
                    <input type="checkbox" id="nameUser" name="nameUser" value="name">
                    <label for="nameUser">Họ Và Tên</label><br>
                    <input type="checkbox" id="emailUser" name="emailUser" value="email">
                    <label for="emailUser">Email</label><br>
                    <input type="checkbox" id="phoneUser" name="phoneUser" value="phone">
                    <label for="phoneUser">Số Điện Thoại</label><br>
                    <div class="wrapperButton">
                        <input class="buttonUserCss" type="submit" value="Áp Dụng" onclick="applySearchFilter()">
                    </div>
                </div>
            `;
            document.body.appendChild(portalRoot);
        }

        function applySearchFilter() {
            const keyword = document.getElementById('search').value;

            const fields = [];
            if (document.getElementById('nameUser').checked) fields.push('fullname');
            if (document.getElementById('emailUser').checked) fields.push('email');
            if (document.getElementById('phoneUser').checked) fields.push('phone');

            if (!keyword || fields.length === 0) {
                alert("Vui lòng nhập từ khóa và chọn ít nhất 1 tiêu chí.");
                return;
            }

            searchUsers(keyword, fields).then(result => {
                    const users = result.data;
                    const tbody = document.querySelector(".data-table tbody");
                    tbody.innerHTML = "";
                    users.forEach(user => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.fullname}</td>
                        <td>${user.dateOfBirth}</td>
                        <td>${user.email}</td>
                        <td>${user.phone}</td>
                        <td>${user.address}</td>
                        <td>${user.gender == 0 ? 'Nam' : 'Nữ'}</td>
                        <td>${user.roleName}</td>
                        <td>${user.createdAt}</td>
                        <td>
                            <button class="btn btn-outline btn-sm" onclick="infoAccount(${user.id})">
                                <i class="fas fa-eye"></i> Xem
                            </button>
                            <button class="btn btn-outline btn-sm" onclick="showFormEditUser(this, ${user.id})">
                                <i class="fa-solid fa-pen"></i> Sửa
                            </button>
                            <button class="btn btn-outline btn-sm" onclick="deleteUser(${user.id})">
                                <i class="fa-solid fa-user-xmark"></i> Xóa
                            </button>
                        </td>
                    `;

                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách người dùng:', error.message);
                });

            closeFormAddUser();
        }

        document.getElementById("searchPhone").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                const keyword = document.getElementById('searchPhone').value;

                const fields = ['phone'];

                if (!keyword || fields.length === 0) {
                    alert("Vui lòng nhập số điện thoại cần tìm");
                    return;
                }

                searchUsers(keyword, fields).then(result => {
                        const users = result.data;
                        const tbody = document.querySelector(".data-table tbody");
                        tbody.innerHTML = "";
                        users.forEach(user => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.fullname}</td>
                            <td>${user.dateOfBirth}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.address}</td>
                            <td>${user.gender == 0 ? 'Nam' : 'Nữ'}</td>
                            <td>${user.roleName}</td>
                            <td>${user.createdAt}</td>
                            <td>
                                <button class="btn btn-outline btn-sm" onclick="infoAccount(${user.id})">
                                    <i class="fas fa-eye"></i> Xem
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="showFormEditUser(this, ${user.id})">
                                    <i class="fa-solid fa-pen"></i> Sửa
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="deleteUser(${user.id})">
                                    <i class="fa-solid fa-user-xmark"></i> Xóa
                                </button>
                            </td>
                        `;

                            tbody.appendChild(tr);
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi lấy danh sách người dùng:', error.message);
                    });

                closeFormAddUser();
            }
        });
    </script>
</body>

</html>