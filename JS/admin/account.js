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

// Hiển thị/ẩn mật khẩu trong input
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
    
    if (isAdmin) {
        alert('Tài khoản Admin có đầy đủ quyền hạn và không thể chỉnh sửa');
        return;
    }

    const accountInfo = {
        username: cells[0].innerText,
        fullName: cells[1].innerText,
        role: cells[3].innerText
    };

    // Các module và quyền theo yêu cầu
    const permissions = {
        "Dashboard": ["view", "export"],
        "Employees": ["view", "create", "edit", "delete"],
        "Products": ["view", "create", "edit", "delete"],
        "Warehouse": ["view", "create", "edit", "delete"],
        "Orders": ["view", "create", "edit", "cancel"],
        "Account & Access": ["view", "edit"],
        "Analytics": ["view", "export"],
        "Sales": ["view", "create", "edit"]
    };

    let permissionHTML = '';
    for (const [module, actions] of Object.entries(permissions)) {
        // Ẩn module "Account & Access" nếu không phải admin
        if (module === "Account & Access") continue;
        
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

// Edit staff account - Hiển thị mật khẩu cho tất cả vai trò
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
        status: cells[4].querySelector('.status').innerText.trim().toLowerCase(),
        statusClass: cells[4].querySelector('.status').className.split(' ')[1]
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
                
                <label for="staff-password">Mật khẩu mới (để trống nếu không đổi)</label>
                <div class="wrapperInputCss password-display">
                    <input class="inputUserCss" type="password" id="staff-password" placeholder="Nhập mật khẩu mới">
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
                    <input type="radio" id="staff-active" name="staff-status" value="active" ${accountInfo.status === 'hoạt động' ? 'checked' : ''}>
                    <label for="staff-active">Hoạt động</label>
                    <input type="radio" id="staff-inactive" name="staff-status" value="inactive" ${accountInfo.status === 'không hoạt động' ? 'checked' : ''}>
                    <label for="staff-inactive">Không hoạt động</label>
                    <input type="radio" id="staff-banned" name="staff-status" value="banned" ${accountInfo.status === 'bị cấm' ? 'checked' : ''}>
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

// Edit customer account - Hiển thị mật khẩu
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
        status: cells[3].querySelector('.status').innerText.trim().toLowerCase(),
        statusClass: cells[3].querySelector('.status').className.split(' ')[1]
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
                
                <label for="customer-password">Mật khẩu mới (để trống nếu không đổi)</label>
                <div class="wrapperInputCss password-display">
                    <input class="inputUserCss" type="password" id="customer-password" placeholder="Nhập mật khẩu mới">
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
                    <input type="radio" id="customer-active" name="customer-status" value="active" ${accountInfo.status === 'hoạt động' ? 'checked' : ''}>
                    <label for="customer-active">Hoạt động</label>
                    <input type="radio" id="customer-inactive" name="customer-status" value="inactive" ${accountInfo.status === 'không hoạt động' ? 'checked' : ''}>
                    <label for="customer-inactive">Không hoạt động</label>
                    <input type="radio" id="customer-banned" name="customer-status" value="banned" ${accountInfo.status === 'bị cấm' ? 'checked' : ''}>
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
    
    // Gửi dữ liệu đến backend
    fetch('/web2/sportswear-webstore/api/account_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            userID: userID,
            permissions: selectedPermissions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã lưu quyền hạn thành công!');
            closeModal();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi kết nối đến server');
    });
}

// Save staff account
function saveStaffAccount(userID, accountID, isAdmin) {
    const accountInfo = {
        username: document.getElementById('staff-username').value,
        password: document.getElementById('staff-password').value,
        fullName: document.getElementById('staff-fullname').value,
        phone: document.getElementById('staff-phone').value,
        role: isAdmin ? 'Admin' : document.getElementById('staff-role').value,
        status: document.querySelector('input[name="staff-status"]:checked').value
    };

    if (!accountInfo.fullName || !accountInfo.phone) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        return;
    }

    // Gửi dữ liệu đến backend
    fetch('/api/update-staff-account', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            userID: userID,
            accountID: accountID,
            ...accountInfo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật thông tin nhân viên thành công!');
            location.reload(); // Tải lại trang để cập nhật dữ liệu
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi kết nối đến server');
    });
}

// Save customer account
function saveCustomerAccount(userID, accountID) {
    const accountInfo = {
        username: document.getElementById('customer-username').value,
        password: document.getElementById('customer-password').value,
        fullName: document.getElementById('customer-fullname').value,
        phone: document.getElementById('customer-phone').value,
        status: document.querySelector('input[name="customer-status"]:checked').value
    };

    if (!accountInfo.fullName || !accountInfo.phone) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        return;
    }

    // Gửi dữ liệu đến backend
    fetch('/api/update-customer-account', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            userID: userID,
            accountID: accountID,
            ...accountInfo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật thông tin khách hàng thành công!');
            location.reload(); // Tải lại trang để cập nhật dữ liệu
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi kết nối đến server');
    });
}

// Initialize with default tab
document.addEventListener('DOMContentLoaded', function() {
    switchTab('staff');
});