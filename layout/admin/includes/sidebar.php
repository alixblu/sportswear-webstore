  <!-- Sidebar -->

  <?php
session_start(); // Start the session

    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id']; // Assuming user ID is stored in the session
        $roleID = $_SESSION['user']['roleid']; // Assuming roleID is stored in the session

        // Create an instance of UserController
        include __DIR__ . '/../../../src/controller/usercontroller.php';
        $userController = new UserController();

        // Get access modules for the user's role
        $userAccess = $userController->getAccessModulesByRoleId($roleID);
    } else {
        $userAccess = []; // No access if user is not logged in
    }
// Define sidebar items with their corresponding module IDs
$sidebarItems = [
    ['id' => 1, 'name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'type' => 'main', 'page' => 'dashboard'],
    ['id' => 2, 'name' => 'Employees', 'icon' => 'fas fa-users', 'type' => 'admin', 'page' => 'user'],
    ['id' => 3, 'name' => 'Products', 'icon' => 'fas fa-box', 'type' => 'admin', 'page' => 'product'],
    ['id' => 4, 'name' => 'Warehouse', 'icon' => 'fas fa-warehouse', 'type' => 'admin', 'page' => 'warehouse'],
    ['id' => 5, 'name' => 'Orders', 'icon' => 'fas fa-file-invoice', 'type' => 'admin', 'page' => 'orders'],
    ['id' => 6, 'name' => 'Coupon & Discount', 'icon' => 'fas fa-tags', 'type' => 'admin', 'page' => 'coupon'],
    ['id' => 7, 'name' => 'Warranty', 'icon' => 'fas fa-shield-alt', 'type' => 'admin', 'page' => 'warranty'],
    ['id' => 8, 'name' => 'Account & Access', 'icon' => 'fas fa-user-lock', 'type' => 'admin', 'page' => 'account'],
    ['id' => 9, 'name' => 'Analytics', 'icon' => 'fas fa-chart-line', 'type' => 'report', 'page' => 'analytics'],
    ['id' => 10, 'name' => 'Sales', 'icon' => 'fas fa-dollar-sign', 'type' => 'report', 'page' => 'sales'],
    ['id' => 11, 'name' => 'Notifications', 'icon' => 'fas fa-bell', 'type' => 'admin', 'page' => 'notifications'],
    ['id' => 12, 'name' => 'Settings', 'icon' => 'fas fa-cog', 'type' => 'admin', 'page' => 'settings'],
];

?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userAccess = <?php echo json_encode($userAccess); ?>; // Pass PHP array to JavaScript

        const navMenu = document.querySelector('.nav-menu');
        const sidebarItems = <?php echo json_encode($sidebarItems); ?>; // Pass PHP array to JavaScript

        // Group items by type
        const groupedItems = sidebarItems.reduce((acc, item) => {
            if (userAccess.includes(item.id)) { // Check if the user has access to the module
                if (!acc[item.type]) {
                    acc[item.type] = []; // Initialize array for this type
                }
                acc[item.type].push(item); // Add item to the corresponding type
            }
            return acc;
        }, {});

        // Create menu headings and items
        for (const [type, items] of Object.entries(groupedItems)) {
            const menuHeading = document.createElement('div');
            menuHeading.className = 'menu-heading';
            menuHeading.textContent = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize the type
            navMenu.appendChild(menuHeading);

            items.forEach(item => {
                const navItem = document.createElement('div');
                navItem.className = 'nav-item';
                navItem.setAttribute('data-page', item.page); // Set the page to load
                navItem.onclick = function() { navigate(this); }; // Call navigate function
                navItem.innerHTML = `<i class="${item.icon}"></i><span class="link">${item.name}</span>`;
                navMenu.appendChild(navItem);
            });
        }
    });

    function navigate(element) {
        const page = element.getAttribute('data-page'); // Get the page from the clicked item
        const mainContentArea = document.querySelector('.main-content-area');

        // Load the corresponding page
        fetch(`./modules/${page}.php`)
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Page not found');
                }
            })
            .then(html => {
                mainContentArea.innerHTML = html; // Update the main content area
            })
            .catch(error => {
                mainContentArea.innerHTML = `<h2>${error.message}</h2>`; // Handle errors
            });
    }
</script>

  <div class="sidebar">

      <div class="nav-menu">
          
      </div>
  </div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">