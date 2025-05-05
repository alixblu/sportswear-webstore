const ACCOUNT_API_URL = '../../src/router/accountRouter.php';

// Utility functions
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

function closeModal() {
    const portalRoot = document.getElementById('portal-root');
    if (portalRoot) {
        portalRoot.remove();
    }
}

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

// API Functions
async function fetchAPI(endpoint, method = 'GET', body = null) {
    try {
        const options = {
            method,
            headers: { 'Content-Type': 'application/json' },
        };

        if (body) {
            options.body = JSON.stringify(body);
        }

        const response = await fetch(endpoint, options);
        const data = await response.json();

        if (!response.ok || data.error) {
            throw new Error(data.error || 'Request failed');
        }

        return data;
    } catch (error) {
        console.error(`API Error (${endpoint}):`, error);
        throw error;
    }
}

const AccountService = {
    getAllAccounts: async () => {
        return fetchAPI(`${ACCOUNT_API_URL}?action=getAllAccounts`);
    },

    getAccountById: async (id) => {
        return fetchAPI(`${ACCOUNT_API_URL}?action=getAccountById&accountId=${id}`);
    },

    createAccount: async (accountData) => {
        return fetchAPI(ACCOUNT_API_URL, 'POST', {
            action: 'createAccount',
            ...accountData
        });
    },

    updateAccount: async (accountData) => {
        return fetchAPI(ACCOUNT_API_URL, 'PUT', {
            action: 'updateAccount',
            ...accountData
        });
    },

    getAllRoles: async () => {
        return fetchAPI(`${ACCOUNT_API_URL}?action=getAllRoles`);
    },

    getPermissions: async (roleId) => {
        return fetchAPI(`${ACCOUNT_API_URL}?action=getPermissions&roleId=${roleId}`);
    },

    filterAccounts: async (filters) => {
        return fetchAPI(ACCOUNT_API_URL, 'POST', {
            action: 'filterAccounts',
            ...filters
        });
    }
};

// UI Functions
function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById(`${tabName}-tab`).classList.add('active');
}

function searchAccounts(type, input) {
    const query = input.value.toLowerCase();
    const rows = document.querySelectorAll(`#${type}-tbody tr`);
    
    rows.forEach(row => {
        const username = row.cells[0].innerText.toLowerCase();
        const fullname = row.cells[1].innerText.toLowerCase();
        const phone = row.cells[2].innerText.toLowerCase();
        row.style.display = (username.includes(query) || fullname.includes(query) || phone.includes(query)) ? '' : 'none';
    });
}

async function showAll() {
    try {
        const { data: accounts } = await AccountService.getAllAccounts();
        const staffTbody = document.querySelector("#staff-tbody");
        const customerTbody = document.querySelector("#customer-tbody");
        
        staffTbody.innerHTML = "";
        customerTbody.innerHTML = "";

        accounts.forEach(account => {
            const tr = document.createElement("tr");
            const statusText = {
                'active': 'Hoạt Động',
                'inactive': 'Không Hoạt Động',
                'banned': 'Bị Cấm'
            }[account.status] || account.status;
            
            const createdAt = new Date(account.accountCreatedAt).toLocaleDateString('vi-VN');
            tr.setAttribute('data-userid', account.userID);
            tr.setAttribute('data-accountid', account.accountID);

            if (account.roleID != 5) {
                tr.innerHTML = `
                    <td>${account.username}</td>
                    <td>${account.fullname}</td>
                    <td>${account.phone}</td>
                    <td>${account.roleName}</td>
                    <td><span class="status ${account.status}"><i class="fas fa-check-circle"></i> ${statusText}</span></td>
                    <td>${createdAt}</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="showPermissionsModal(this, ${account.roleID})">
                            <i class="fas fa-user-shield"></i> Quyền hạn
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="showFormEdit(this, ${account.accountID}, 'staff')">
                            <i class="fa-solid fa-pen"></i> Sửa
                        </button>
                    </td>
                `;
                staffTbody.appendChild(tr);
            } else {
                tr.innerHTML = `
                    <td>${account.username}</td>
                    <td>${account.fullname}</td>
                    <td>${account.phone}</td>
                    <td><span class="status ${account.status}"><i class="fas fa-check-circle"></i> ${statusText}</span></td>
                    <td>${createdAt}</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="showFormEdit(this, ${account.accountID}, 'customer')">
                            <i class="fa-solid fa-pen"></i> Sửa
                        </button>
                    </td>
                `;
                customerTbody.appendChild(tr);
            }
        });
    } catch (error) {
        showToast(error.message || 'Lỗi khi lấy danh sách tài khoản', 'error');
    }
}

// Account CRUD Operations
async function add() {
    const accountData = {
        username: document.getElementById('username').value.trim(),
        password: document.getElementById('password').value.trim(),
        fullname: document.getElementById('fullname').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        roleId: document.getElementById('roleId').value.trim(),
        status: document.querySelector('input[name="status"]:checked').value,
        email: document.getElementById('email').value.trim() || null,
        address: document.getElementById('address').value.trim() || null,
        gender: document.getElementById('gender').value.trim() || null,
        dateOfBirth: document.getElementById('dateOfBirth').value.trim() || null
    };

    if (!accountData.username || !accountData.password || !accountData.fullname || 
        !accountData.phone || !accountData.roleId || !accountData.status) {
        showToast('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
        return;
    }

    try {
        await AccountService.createAccount(accountData);
        showToast('Thêm tài khoản thành công', 'success');
        closeModal();
        showAll();
    } catch (error) {
        showToast(error.message || 'Lỗi khi thêm tài khoản', 'error');
    }
}

async function showFormEdit(button, id, type) {
    try {
        const [{ data: account }, { data: roles }] = await Promise.all([
            AccountService.getAccountById(id),
            AccountService.getAllRoles()
        ]);

        const isStaff = type === 'staff';
        const isAdmin = isStaff && account.roleName === 'Admin';
        
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
            <div class="formUserCss">
                <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <div class="infoCss">Chỉnh sửa tài khoản ${isStaff ? 'nhân viên' : 'khách hàng'}</div>
                    
                    <label for="username">Tên đăng nhập</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="username" value="${account.username}" readonly>
                    </div>
                    
                    <label for="new-password">Mật khẩu mới (để trống nếu không đổi)</label>
                    <div class="wrapperInputCss password-display">
                        <input class="inputUserCss" type="password" id="new-password" placeholder="Nhập mật khẩu mới">
                        <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                    </div>
                    
                    <label for="fullname">Họ và tên</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="fullname" value="${account.fullname}">
                    </div>
                    
                    <label for="phone">Số điện thoại</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="tel" id="phone" value="${account.phone}">
                    </div>
                    
                    ${isStaff && !isAdmin ? `
                    <label for="roleId">Vai trò</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="roleId">
                            ${roles.filter(role => role.ID != 5).map(role => `
                                <option value="${role.ID}" ${role.ID == account.roleID ? 'selected' : ''}>${role.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    ` : ''}
                    
                    <label>Trạng thái</label>
                    <div class="genderCss">
                        <input type="radio" id="active" name="status" value="active" ${account.status === 'active' ? 'checked' : ''}>
                        <label for="active">Hoạt động</label>
                        <input type="radio" id="inactive" name="status" value="inactive" ${account.status === 'inactive' ? 'checked' : ''}>
                        <label for="inactive">Không hoạt động</label>
                        <input type="radio" id="banned" name="status" value="banned" ${account.status === 'banned' ? 'checked' : ''}>
                        <label for="banned">Bị cấm</label>
                    </div>
                    
                    <label for="email">Email</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="email" id="email" value="${account.email || ''}">
                    </div>
                    
                    <label for="address">Địa chỉ</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="text" id="address" value="${account.address || ''}">
                    </div>
                    
                    <label for="gender">Giới tính</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="gender">
                            <option value="male" ${account.gender === 'male' ? 'selected' : ''}>Nam</option>
                            <option value="female" ${account.gender === 'female' ? 'selected' : ''}>Nữ</option>
                            <option value="other" ${account.gender === 'other' ? 'selected' : ''}>Khác</option>
                        </select>
                    </div>
                    
                    <label for="dateOfBirth">Ngày sinh</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" type="date" id="dateOfBirth" value="${account.dateOfBirth || ''}">
                    </div>
                    
                    <div class="wrapperButton">
                        <button class="buttonUserCss" onclick="edit(${id}, ${isAdmin})">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);
    } catch (error) {
        showToast(error.message || 'Lỗi khi lấy thông tin tài khoản', 'error');
    }
}

async function edit(id, isAdmin) {
    const accountData = {
        accountId: id,
        username: document.getElementById('username').value.trim(),
        fullname: document.getElementById('fullname').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        roleId: isAdmin ? 1 : document.getElementById('roleId')?.value.trim() || 5,
        status: document.querySelector('input[name="status"]:checked').value,
        email: document.getElementById('email').value.trim() || null,
        address: document.getElementById('address').value.trim() || null,
        gender: document.getElementById('gender').value.trim() || null,
        dateOfBirth: document.getElementById('dateOfBirth').value.trim() || null
    };

    const newPassword = document.getElementById('new-password').value.trim();
    if (newPassword) {
        accountData.password = newPassword;
    }

    if (!accountData.username || !accountData.fullname || !accountData.phone || !accountData.roleId || !accountData.status) {
        showToast('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
        return;
    }

    try {
        await AccountService.updateAccount(accountData);
        showToast('Cập nhật tài khoản thành công', 'success');
        closeModal();
        showAll();
    } catch (error) {
        showToast(error.message || 'Lỗi khi cập nhật tài khoản', 'error');
    }
}

// Filter Functions
async function showFormFilter(type) {
    try {
        const { data: roles } = await AccountService.getAllRoles();
        
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
            <div class="wrapperFilterCss">
                <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <div class="infoCss">Lọc tài khoản ${type === 'staff' ? 'nhân viên' : 'khách hàng'}</div>
                    
                    <label for="filter-status">Trạng thái</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="filter-status">
                            <option value="all">Tất cả</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                            <option value="banned">Bị cấm</option>
                        </select>
                    </div>
                    
                    ${type === 'staff' ? `
                    <label for="filter-role">Vai trò</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="filter-role">
                            <option value="all">Tất cả</option>
                            ${roles.filter(role => role.ID != 5).map(role => `
                                <option value="${role.ID}">${role.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    ` : ''}
                    
                    <div class="wrapperButton">
                        <button class="buttonUserCss" onclick="applyFilter('${type}')">
                            <i class="fas fa-filter"></i> Áp dụng bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);
    } catch (error) {
        showToast(error.message || 'Lỗi khi lấy danh sách vai trò', 'error');
    }
}

async function applyFilter(type) {
    const filters = {
        status: document.getElementById('filter-status').value,
        type: type === 'staff' ? 'staff' : 'customer'
    };

    if (type === 'staff') {
        const roleValue = document.getElementById('filter-role').value;
        if (roleValue !== 'all') {
            filters.roleID = roleValue;
        }
    }

    try {
        const { data: accounts } = await AccountService.filterAccounts(filters);
        const tbody = document.querySelector(`#${type}-tbody`);
        tbody.innerHTML = "";

        accounts.forEach(account => {
            const tr = document.createElement("tr");
            const statusText = {
                'active': 'Hoạt Động',
                'inactive': 'Không Hoạt Động',
                'banned': 'Bị Cấm'
            }[account.status] || account.status;
            
            const createdAt = new Date(account.accountCreatedAt).toLocaleDateString('vi-VN');
            tr.setAttribute('data-userid', account.userID);
            tr.setAttribute('data-accountid', account.accountID);

            if (type === 'staff') {
                tr.innerHTML = `
                    <td>${account.username}</td>
                    <td>${account.fullname}</td>
                    <td>${account.phone}</td>
                    <td>${account.roleName}</td>
                    <td><span class="status ${account.status}"><i class="fas fa-check-circle"></i> ${statusText}</span></td>
                    <td>${createdAt}</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="showPermissionsModal(this, ${account.roleID})">
                            <i class="fas fa-user-shield"></i> Quyền hạn
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="showFormEdit(this, ${account.accountID}, 'staff')">
                            <i class="fa-solid fa-pen"></i> Sửa
                        </button>
                    </td>
                `;
            } else {
                tr.innerHTML = `
                    <td>${account.username}</td>
                    <td>${account.fullname}</td>
                    <td>${account.phone}</td>
                    <td><span class="status ${account.status}"><i class="fas fa-check-circle"></i> ${statusText}</span></td>
                    <td>${createdAt}</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="showFormEdit(this, ${account.accountID}, 'customer')">
                            <i class="fa-solid fa-pen"></i> Sửa
                        </button>
                    </td>
                `;
            }
            tbody.appendChild(tr);
        });
        
        closeModal();
    } catch (error) {
        showToast(error.message || 'Lỗi khi lọc tài khoản', 'error');
    }
}

// Permission Functions
async function showPermissionsModal(button, roleId) {
    try {
        const row = button.closest('tr');
        const cells = row.querySelectorAll('td');
        const isAdmin = cells[3].innerText.trim() === 'Admin';

        if (isAdmin) {
            showToast('Tài khoản Admin có đầy đủ quyền hạn và không thể chỉnh sửa', 'error');
            return;
        }

        const { data: permissions } = await AccountService.getPermissions(roleId);
        const accountInfo = {
            username: cells[0].innerText,
            fullName: cells[1].innerText,
            role: cells[3].innerText
        };

        // Giả định danh sách module (nên thay bằng API getAllModules nếu có)
        const modules = [
            { name: "Dashboard", actions: ["view", "export"] },
            { name: "Employees", actions: ["view", "create", "edit"] },
            { name: "Products", actions: ["view", "create", "edit"] },
            { name: "Warehouse", actions: ["view", "create", "edit"] },
            { name: "Orders", actions: ["view", "create", "edit", "cancel"] },
            { name: "Coupon & Discount", actions: ["view", "create", "edit"] },
            { name: "Warranty", actions: ["view", "create", "edit"] },
            { name: "Account & Access", actions: ["view", "edit"] },
            { name: "Analytics", actions: ["view", "export"] },
            { name: "Sales", actions: ["view", "edit"] }
        ];

        let permissionHTML = modules.map(module => `
            <div class="permission-group">
                <div class="permission-group-title">${module.name}</div>
                <div class="permission-checkboxes">
                    ${module.actions.map(action => `
                        <div class="permission-option">
                            <input type="checkbox" id="perm-${module.name}-${action}" 
                                   ${permissions.includes(action) ? 'checked' : ''} disabled>
                            <label for="perm-${module.name}-${action}">${action.charAt(0).toUpperCase() + action.slice(1)}</label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');

        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
            <div class="formUserCss">
                <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <div class="infoCss">Quản lý quyền hạn</div>
                    <div><strong>Tài khoản:</strong> ${accountInfo.username}</div>
                    <div><strong>Họ tên:</strong> ${accountInfo.fullName}</div>
                    <div><strong>Vai trò hiện tại:</strong> ${accountInfo.role}</div>
                    <div style="color: #f44336; margin: 10px 0;">
                        Lưu ý: Quyền hạn được quản lý theo vai trò. Để thay đổi quyền, vui lòng thay đổi vai trò của tài khoản trong form chỉnh sửa.
                    </div>
                    ${permissionHTML}
                    <div class="wrapperButton">
                        <button class="buttonUserCss" onclick="savePermissions(${roleId})">
                            <i class="fas fa-save"></i> Lưu quyền hạn
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);
    } catch (error) {
        showToast(error.message || 'Lỗi khi lấy quyền hạn', 'error');
    }
}

async function savePermissions(roleId) {
    showToast('Không thể lưu quyền hạn. Vui lòng thay đổi vai trò của tài khoản để cập nhật quyền hạn', 'error');
    closeModal();
}

// Initialize
document.getElementById("addBtn").onclick = async () => {
    try {
        const { data: roles } = await AccountService.getAllRoles();
        
        const portalRoot = document.createElement('div');
        portalRoot.id = 'portal-root';
        portalRoot.innerHTML = `
            <div class="formUserCss">
                <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()" style="cursor: pointer;"></i></div>
                <div class="wrapperCss">
                    <div class="infoCss">Thêm tài khoản mới</div>
                    
                    <label for="username">Tên đăng nhập</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="Nhập tên đăng nhập" type="text" id="username">
                    </div>
                    
                    <label for="password">Mật khẩu</label>
                    <div class="wrapperInputCss password-display">
                        <input class="inputUserCss" placeholder="Nhập mật khẩu" type="password" id="password">
                        <i class="fas fa-eye show-password" onclick="togglePasswordInput(this)"></i>
                    </div>
                    
                    <label for="fullname">Họ và tên</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="Nhập họ và tên" type="text" id="fullname">
                    </div>
                    
                    <label for="phone">Số điện thoại</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="Nhập số điện thoại" type="tel" id="phone">
                    </div>
                    
                    <label for="roleId">Vai trò</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="roleId">
                            ${roles.map(role => `<option value="${role.ID}">${role.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <label for="status">Trạng thái</label>
                    <div class="genderCss">
                        <input type="radio" id="active" name="status" value="active" checked>
                        <label for="active">Hoạt động</label>
                        <input type="radio" id="inactive" name="status" value="inactive">
                        <label for="inactive">Không hoạt động</label>
                        <input type="radio" id="banned" name="status" value="banned">
                        <label for="banned">Bị cấm</label>
                    </div>
                    
                    <label for="email">Email</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="Nhập email" type="email" id="email">
                    </div>
                    
                    <label for="address">Địa chỉ</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="Nhập địa chỉ" type="text" id="address">
                    </div>
                    
                    <label for="gender">Giới tính</label>
                    <div class="wrapperInputCss">
                        <select class="selectUser" id="gender">
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    
                    <label for="dateOfBirth">Ngày sinh</label>
                    <div class="wrapperInputCss">
                        <input class="inputUserCss" placeholder="YYYY-MM-DD" type="date" id="dateOfBirth">
                    </div>
                    
                    <div class="wrapperButton">
                        <button class="buttonUserCss" onclick="add()">
                            <i class="fas fa-plus"></i> Thêm tài khoản
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(portalRoot);
    } catch (error) {
        showToast(error.message || 'Lỗi khi lấy danh sách vai trò', 'error');
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    showAll();
});