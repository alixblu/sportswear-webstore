<!-- Header -->
<div class="header">
  <div class="header-left">
    <div class="dashboard_open" id="dashboard_switch" onclick="toggle_sidebar()">
      <i class="fas fa-bars fa-xl"></i>
    </div>
    <div class="search-bar">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search..." />
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
    <div class="user-profile">
      <div class="profile-img">JD</div>
      <div class="user-info">
        <div class="user-name">John Doe</div>
        <div class="user-role">Administrator</div>
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
</script>