<?php
require_once __DIR__ . '/../../../src/repository/accountrepository.php';
$accountRepository = new AccountRepository();
?>
<!-- Header -->
<div class="header">
  <div class="header-left">
    <div class="dashboard_open" id="dashboard_switch" onclick="toggle_sidebar()">
      <i class="fas fa-bars fa-xl"></i>
    </div>
  </div>
  <div class="header-actions">
    <div class="notification">
      <i class="fas fa-bell"></i>
      <div class="badge">3</div>
    </div>
    <div class="notification">
      <i class="fas fa-envelope"></i>
      <div class="badge">5</div>
    </div>
    <div class="user-profile" onclick="toggleUserMenu()">
      <div class="profile-img">
        <?php 
            $email = $_SESSION['user']['username'];
            echo $email[0]; 
        ?>
      </div>
      <div class="user-info">
        <div class="user-name">
          <?php 
            $email = $_SESSION['user']['username'];
            echo explode('@', $email)[0]; 
          ?>
        </div>
        <div class="user-role">
          <?php 
            $roleid = $_SESSION['user']['roleid'];
            $rolename = $accountRepository->getRole($roleid);
            echo $rolename;
          ?>
        </div>
      </div>
      <div class="user-menu" id="userMenu">
        <div class="menu-item" onclick="handleLogout()">
          <i class="fas fa-sign-out-alt"></i>
          <span>Đăng xuất</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggle_sidebar() {
    let sidebar = document.getElementById('sidebar')
    let mainContentArea = document.getElementsByClassName('main-content-area')

    sidebar.classList.toggle('collapsed')
    mainContentArea[0].classList.toggle('sidebar-collapsed')
  }

  function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('show');
  }

  // Close menu when clicking outside
  document.addEventListener('click', function(event) {
    const menu = document.getElementById('userMenu');
    const userProfile = document.querySelector('.user-profile');
    if (!userProfile.contains(event.target)) {
      menu.classList.remove('show');
    }
  });

  function handleLogout() {
    fetch('/sportswear-webstore/layout/auth.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'submitLogout=1'
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = '/sportswear-webstore/index.php';
      } else {
        alert('Đăng xuất thất bại: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Đăng xuất thất bại: ' + error.message);
    });
  }

  const getrole = async (id) => {
    try {
        const response = await fetch(`/sportswear-webstore/src/router/accountRouter.php?action=getAllRoles`, {
            method: 'GET',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        console.log(response);
        if (!response.ok) {
            throw new Error('Error fetching roles');
        }

        const data = await response.json();
        // Check if data is an array or has a data property
        if (Array.isArray(data)) {
            const item = data.find(d => d.ID === id);
            return item ? item.name : "";
        
        } else {
            throw new Error('Invalid response format header.php');
        }
    } catch (error) {
        console.error('Error in getrole:', error);
        throw error;
    }
  }
</script>

<style>
.user-profile {
  position: relative;
  cursor: pointer;
}

.user-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  border-radius: 4px;
  min-width: 150px;
  z-index: 1000;
}

.user-menu.show {
  display: block;
}

.menu-item {
  padding: 10px 15px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #333;
  transition: background-color 0.2s;
}

.menu-item:hover {
  background-color: #f5f5f5;
}

.menu-item i {
  width: 20px;
  text-align: center;
}
</style>