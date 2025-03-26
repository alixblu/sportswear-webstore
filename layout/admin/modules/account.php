

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
                                    <button class="btn btn-outline btn-sm" onclick="viewAccount('staff', this)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-outline btn-sm" onclick="editStaffAccount(this)">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <button class="btn btn-outline btn-sm" onclick="deleteAccount('staff')">
                                        <i class="fa-solid fa-user-xmark"></i> Delete
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
                                    <button class="btn btn-outline btn-sm" onclick="viewAccount('customer', this)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
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

        // View account details
        function viewAccount(type, button) {
            const row = button.closest('tr');
            const cells = row.querySelectorAll('td');
            
            let accountInfo = {
                username: cells[0].innerText,
                fullName: cells[1].innerText,
                email: cells[2].innerText,
                phone: cells[3].innerText,
                status: cells[type === 'staff' ? 5 : 4].innerText,
                date: cells[type === 'staff' ? 6 : 5].innerText
            };

            if (type === 'staff') {
                accountInfo.role = cells[4].innerText;
            }

            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="CloseCss"><i class="fa-solid fa-xmark" onclick="closeModal()"></i></div>
                    <div class="wrapperCss">
                        <div class="infoCss">Account Details</div>
                        <div>Username: ${accountInfo.username}</div>
                        <div>Full Name: ${accountInfo.fullName}</div>
                        <div>Email: ${accountInfo.email}</div>
                        <div>Phone: ${accountInfo.phone}</div>
                        ${type === 'staff' ? `<div>Role: ${accountInfo.role}</div>` : ''}
                        <div>Status: ${accountInfo.status}</div>
                        <div>${type === 'staff' ? 'Created' : 'Registered'} At: ${accountInfo.date}</div>
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
                            <option value="admin" ${accountInfo.role === 'Administrator' ? 'selected' : ''}>Administrator</option>
                            <option value="manager" ${accountInfo.role === 'Manager' ? 'selected' : ''}>Manager</option>
                            <option value="staff" ${accountInfo.role === 'Staff' ? 'selected' : ''}>Staff</option>
                        </select>
                        
                        <label>Status</label>
                        <div class="genderCss">
                            <input type="radio" id="staff-active" name="staff-status" ${accountInfo.status === 'active' ? 'checked' : ''}>
                            <label for="staff-active">Active</label>
                            <input type="radio" id="staff-inactive" name="staff-status" ${accountInfo.status === 'inactive' ? 'checked' : ''}>
                            <label for="staff-inactive">Inactive</label>
                        </div>
                        
                        <div class="wrapperButton">
                            <input class="buttonUserCss" type="submit" value="Save Changes" onclick="saveStaffAccount()">
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
                            <input class="buttonUserCss" type="submit" value="Save Changes" onclick="saveCustomerAccount()">
                        </div>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';
        }

        // Delete account
        function deleteAccount(type) {
            const portalRoot = document.getElementById('portal-root');
            portalRoot.innerHTML = `
                <div class="formUserCss">
                    <div class="titleDeleteUserCss">Are you sure you want to delete this ${type} account?</div>
                    <div class="deleteUserCss">
                        <button id="cancelDelete">Cancel</button>
                        <button id="confirmDelete">Confirm</button>
                    </div>
                </div>
            `;
            portalRoot.style.display = 'flex';

            document.getElementById('confirmDelete').addEventListener('click', function() {
                alert(`${type} account deleted successfully.`);
                closeModal();
            });

            document.getElementById('cancelDelete').addEventListener('click', closeModal);
        }

        // Close modal
        function closeModal() {
            document.getElementById('portal-root').style.display = 'none';
        }

        // Save functions (cần tích hợp với backend)
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