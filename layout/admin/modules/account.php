<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!--=============== REMIXICONS ===============-->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <title>Account Management</title>
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
    }

    /* Giữ nguyên toàn bộ CSS từ user.php */
    .actionUser {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .search-box input {
        flex: 1;
        border: none;
        background-color: transparent;
        outline: none;
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

    /* Thêm CSS cho tab */
    .tab-container {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }
    .tab {
        padding: 10px 20px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }
    .tab.active {
        border-bottom: 2px solid var(--primary);
        color: var(--primary);
        font-weight: bold;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    
    /* Giữ nguyên các style khác từ user.php */
    /* ... */
    
  </style>
</head>
<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Account Management</div>
            <div class="action-buttons">
                <button id="exportBtn" class="btn btn-outline">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('staff')">Staff Accounts</div>
            <div class="tab" onclick="switchTab('customer')">Customer Accounts</div>
        </div>

        <!-- Staff Accounts -->
        <div id="staff-tab" class="tab-content active">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-user-tie"></i> Staff Account Information</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="ri-search-line"></i>
                                <input type="text" placeholder="Search by username or phone">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('staff')">
                                <i class="fa-solid fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="actionUser">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>admin01</td>
                                <td>Nguyen Van A</td>
                                <td>admin@example.com</td>
                                <td>0912345678</td>
                                <td>Administrator</td>
                                <td><span class="status active">Active</span></td>
                                <td>01/01/2023</td>
                                <td>
                                    <button class="btn btn-outline btn-sm" onclick="showPermissionsModal(this)">
                                        <i class="fas fa-user-shield"></i> Permissions
                                    </button>
                                    <button class="btn btn-outline btn-sm" onclick="editStaffAccount(this)">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Accounts -->
        <div id="customer-tab" class="tab-content">
            <div class="stats-cards">
                <div class="table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-users"></i> Customer Account Information</h3>
                        <div class="wrapperFilter">
                            <div class="search-box">
                                <i class="ri-search-line"></i>
                                <input type="text" placeholder="Search by email or phone">
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="showFormFilter('customer')">
                                <i class="fa-solid fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Registered At</th>
                                <th class="actionUser">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>customer01</td>
                                <td>Le Van B</td>
                                <td>customer@example.com</td>
                                <td>0987654321</td>
                                <td><span class="status active">Active</span></td>
                                <td>15/02/2023</td>
                                <td>
                                    <button class="btn btn-outline btn-sm" onclick="editCustomerAccount(this)">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Portal -->
    <div id="portal-root"></div>

    <script>
        // Tab switching
        function switchTab(tabName) {
            // Update tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
            
            // Update content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // Show permissions modal
        function showPermissionsModal(button) {
            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');
            
            const accountInfo = {
                username: cells[0].innerText,
                fullName: cells[1].innerText,
                role: cells[4].innerText
            };

            // Sample permissions (in a real app, these would come from the server)
            const permissions = {
                "Dashboard": ["view", "export"],
                "User Management": ["view", "create", "edit", "delete"],
                "Product Management": ["view", "create", "edit", "delete"],
                "Order Management": ["view", "create", "edit", "cancel"],
                "Reports": ["view", "generate", "export"]
            };

            let permissionHTML = '';
            for (const [module, actions] of Object.entries(permissions)) {
                permissionHTML += `
                    <div class="permission-group">
                        <div class="permission-group-title">${module}</div>
                        <div class="permission-checkboxes">
                `;
                
                for (const action of actions) {
                    // Random checked state for demo (replace with actual permission check)
                    const isChecked = Math.random() > 0.5;
                    permissionHTML += `
                        <div class="permission-option">
                            <input type="checkbox" id="perm-${module}-${action}" ${isChecked ? 'checked' : ''}>
                            <label for="perm-${module}-${action}">${action.charAt(0).toUpperCase() + action.slice(1)}</label>
                        </div>
                    `;
                }
                
                permissionHTML += `</div></div>`;
            }

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Permission Management</div>
                        <div><strong>Account:</strong> ${accountInfo.username}</div>
                        <div><strong>Name:</strong> ${accountInfo.fullName}</div>
                        <div><strong>Current Role:</strong> ${accountInfo.role}</div>
                        
                        ${permissionHTML}
                        
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="savePermissions()">
                                <i class="fas fa-save"></i> Save Permissions
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Edit staff account
        function editStaffAccount(button) {
            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');
            
            const accountInfo = {
                username: cells[0].innerText,
                fullName: cells[1].innerText,
                email: cells[2].innerText,
                phone: cells[3].innerText,
                role: cells[4].innerText,
                status: cells[5].querySelector('.status').innerText.trim() === 'Active' ? 'active' : 'inactive',
                date: cells[6].innerText
            };

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Edit Staff Account</div>
                        
                        <label for="staff-username">Username</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-username" value="${accountInfo.username}" readonly>
                        </div>
                        
                        <label for="staff-fullname">Full Name</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-fullname" value="${accountInfo.fullName}">
                        </div>
                        
                        <label for="staff-email">Email</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-email" value="${accountInfo.email}">
                        </div>
                        
                        <label for="staff-phone">Phone</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="staff-phone" value="${accountInfo.phone}">
                        </div>
                        
                        <label for="staff-role">Role</label>
                        <select class="selectUser" id="staff-role">
                            <option value="Administrator" ${accountInfo.role === 'Administrator' ? 'selected' : ''}>Administrator</option>
                            <option value="Manager" ${accountInfo.role === 'Manager' ? 'selected' : ''}>Manager</option>
                            <option value="Staff" ${accountInfo.role === 'Staff' ? 'selected' : ''}>Staff</option>
                        </select>
                        
                        <label>Status</label>
                        <div class="genderCss">
                            <input type="radio" id="staff-active" name="staff-status" ${accountInfo.status === 'active' ? 'checked' : ''}>
                            <label for="staff-active">Active</label>
                            <input type="radio" id="staff-inactive" name="staff-status" ${accountInfo.status === 'inactive' ? 'checked' : ''}>
                            <label for="staff-inactive">Inactive</label>
                        </div>
                        
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="saveStaffAccount()">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Edit customer account
        function editCustomerAccount(button) {
            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');
            
            const accountInfo = {
                username: cells[0].innerText,
                fullName: cells[1].innerText,
                email: cells[2].innerText,
                phone: cells[3].innerText,
                status: cells[4].querySelector('.status').innerText.trim() === 'Active' ? 'active' : 'inactive',
                date: cells[5].innerText
            };

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Edit Customer Account</div>
                        
                        <label for="customer-username">Username</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-username" value="${accountInfo.username}" readonly>
                        </div>
                        
                        <label for="customer-fullname">Full Name</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-fullname" value="${accountInfo.fullName}">
                        </div>
                        
                        <label for="customer-email">Email</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-email" value="${accountInfo.email}">
                        </div>
                        
                        <label for="customer-phone">Phone</label>
                        <div class="wrapperInputCss">
                            <input class="inputUserCss" type="text" id="customer-phone" value="${accountInfo.phone}">
                        </div>
                        
                        <label>Status</label>
                        <div class="genderCss">
                            <input type="radio" id="customer-active" name="customer-status" ${accountInfo.status === 'active' ? 'checked' : ''}>
                            <label for="customer-active">Active</label>
                            <input type="radio" id="customer-inactive" name="customer-status" ${accountInfo.status === 'inactive' ? 'checked' : ''}>
                            <label for="customer-inactive">Inactive</label>
                        </div>
                        
                        <div class="wrapperButton">
                            <button class="buttonUserCss" onclick="saveCustomerAccount()">
                                <i class="fas fa-save"></i> Save Changes
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
        }

        // Save functions (to be integrated with backend)
        function savePermissions() {
            alert('Permissions saved successfully!');
            closeModal();
        }

        function saveStaffAccount() {
            alert('Staff account changes saved!');
            closeModal();
        }

        function saveCustomerAccount() {
            alert('Customer account changes saved!');
            closeModal();
        }
    </script>
</body>
</html>