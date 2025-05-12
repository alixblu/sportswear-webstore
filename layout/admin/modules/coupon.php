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
        gap:5px
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
    .wrapperButton{
        display: flex;
        gap: 10px
    }
    .CloseCss{
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
    .infoCss{
        margin-bottom: 15px;
        font-weight: bold; 
    }
    .status.active {
        background-color: rgba(76, 201, 240, 0.15);
        color: #0891b2;
        border: 1px solid rgba(76, 201, 240, 0.3);
    }
    .deleteUserCss{
        display: flex;
        justify-content: center;
        gap:30px;
        margin: 10px;
    }
    .titleDeleteUserCss{
        margin: 10px;
        font-size: 17px
    }
    #cancelDelete{
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, var(--secondary), var(--primary));
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 15px;
    }
    #confirmDelete{
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, var(--secondary), var(--primary));
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 15px;
    }
    .wrapperFilterCss{
        background-color:white;
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
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
        0%   { opacity: 0; transform: translateY(20px); }
        10%  { opacity: 1; transform: translateY(0); }
        90%  { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(20px); }
    }

    </style>
</head>
<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Coupon </div>
            <div class="action-buttons">
                <button id="addBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>
        </div>
        <div class="stats-cards">
            <div class="table-card">
                <div class="card-title">
                    <h3><i class="fa-solid fa-user"></i>Mã Giảm</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                        <th>STT</th>
                        <th>Tên Mã</th>
                        <th>Phần Trăm Giảm</th>
                        <th>Hiệu Lực</th>
                        <th>Trạng Thái</th>
                        <th>Chức Năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Giảm 5% cho khách hàng mới</td>
                            <td>5%</td>
                            <td>12</td>
                            <td><span class="status active"><i class="fas fa-check-circle"></i>Hoạt Động</span></td>
                            <td >
                                <button class="btn btn-outline btn-sm" onclick="infoAccount()">
                                <i class="fas fa-eye"></i> Xem
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="showFormEditUser(this)">
                                <i class="fa-solid fa-pen"></i> Sửa
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="deleteUser()">
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
</body>
<script src="/sportswear-webstore/JS/admin/coupon.js"></script>
<script>
    showAll();
    function showAll() {
        getAllCoupons()
        .then(result => {
            let stt =1;
            const coupons = result;
            const tbody = document.querySelector(".data-table tbody");
            tbody.innerHTML = ""; 
            coupons.forEach(coupon => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${stt}</td>
                    <td>${coupon.name}</td>
                    <td>${coupon.percent}</td>
                    <td>${coupon.duration}</td>
                    <td>${coupon.status == 'active' ? '<span class="status active"><i class="fas fa-check-circle"></i>Hoạt Động</span>'  :
                            '<span class="status cancelled"><i class="fas fa-check-circle"></i>Đã Dừng</span>' 
                    }</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="showFormEdit(this, ${coupon.ID})">
                            <i class="fa-solid fa-pen"></i> Sửa
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="deletecoupon(${coupon.ID})">
                            <i class="fa-solid fa-user-xmark"></i> Xóa
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
                stt=stt+1
            });
        })
        .catch(error => {
            console.error('Lỗi khi lấy danh sách người dùng:', error.message);
        });
    }

    const addnewbtn = document.getElementById("addBtn")
    addnewbtn.onclick = () =>{
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML=`
            <div class="formUserCss">
                <div class="CloseCss" ><i class="fa-solid fa-xmark" onclick="closeFormAdd()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <label for="name">Tên Mã</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="NHập Tên Mã" type="text" id="name">
                    </div>
                    
                    <label for="percent">Phần Trăm Giảm</label>

                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="VD: 5" type="text" id="percent">
                    </div>

                    
                    <label for="duration">Hiệu Lực	</label>
                    
                    <div class="wrapperInputCss">
                        <input type="text" placeholder="VD: 12" class="inputUserCss" id="duration">
                    </div>

                    <div class="wrapperButton">
                        <input class="buttonUserCss" type="submit" value="Thêm Mã" onclick="add()">
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);
    }
    function closeFormAdd(){
        const portalRoot = document.getElementById('portal-root');
        if (portalRoot) {
            portalRoot.remove();
        }
    }
    function add(){
        const name = document.getElementById('name').value.trim();
        const percent = document.getElementById('percent').value.trim();
        const duration = document.getElementById('duration').value.trim();


        if (!name || !percent || !duration) {
            alert('Vui lòng điền đầy đủ thông tin.');
            return;
        }

        createCoupon(name,percent, duration)
        .then(response => {
            if (response.status === 200) {
                showToast('Thêm Mã thành công!', 'success');
                closeFormAdd();
                showAll();
            } 
            else {
                showToast(response.data, 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
        });
    }


    function showFormEdit(button,id){
        const row = button.closest('tr');
        const couponInfo = {
            name: row.children[1].innerText.trim(),
            percent: row.children[2].innerText.trim(),
            duration: row.children[3].innerText.trim(),
            status: row.children[4].innerText.trim(),
        };
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
            <div class="formUserCss">
                <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeFormAdd()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <label for="name">Tên Mã</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="name" value="${couponInfo.name}">
                    </div>
                    
                    <label for="percent">Phần Trăm Giảm</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="percent" value="${couponInfo.percent}">
                    </div>

                    <label for="duration">Hiệu Lực</label>
                    <div class="wrapperInputCss">
                        <input type="text" class="inputUserCss" id="duration" value="${couponInfo.duration}">
                    </div>

                    <label for="status">Trạng Thái</label>
                    <div class="wrapperInputCss">
                        <select class="inputUserCss" id="status">
                            <option value="active" ${couponInfo.status === 'Hoạt Động' ? 'selected' : ''}>Hoạt Động</option>
                            <option value="inactive" ${couponInfo.status !== 'Hoạt Động' ? 'selected' : ''}>Không Hoạt Động</option>
                        </select>
                    </div>

                    <div class="wrapperButton">
                        <input class="buttonUserCss" type="submit" value="Thêm Mã" onclick="edit(${id})">
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(portalRoot);
    }
    function edit(id){
        const name = document.getElementById('name').value.trim();
        const percent = document.getElementById('percent').value.trim();
        const duration = document.getElementById('duration').value.trim();
        const status = document.getElementById('status').value.trim();

        if (!name || !percent || !duration || !status) {
            alert('Vui lòng điền đầy đủ thông tin.');
            return;
        }

        updateCoupon(id, name, percent, duration, status)
        .then(response => {
            if (response.status === 200) {
                showToast('Cập nhật thành công!', 'success');
                closeFormAdd();
                showAll();
            } else {
                showToast(response.data, 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra khi cập nhật người dùng.', 'error');
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

    function deletecoupon(id){
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML=`
            <div class="formUserCss">
                <div class="titleDeleteUserCss">Bạn có chắc chắn muốn xóa không?</div>
                <div class="deleteUserCss">
                    <button id="cancelDelete">Hủy</button>
                    <button id="confirmDelete">Xác nhận</button>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);


        document.getElementById('confirmDelete').addEventListener('click', function () {
            deleteCoupon(id)
            .then(response => {
                if (response.status === 200) {
                    showToast('Xóa thành công!', 'success');
                    closeFormAdd();
                    showAll();
                } else {
                    showToast(response.data, 'error');
                    closeFormAdd();
                }
            })
            .catch(error => {
                showToast('Có lỗi xảy ra.', 'error');
                closeFormAdd();
            });
            showAll();
        });

        document.getElementById('cancelDelete').addEventListener('click', function () {
            closeFormAdd();
        });
    }
</script>
</html>

