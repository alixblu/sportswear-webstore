<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../../css/admin/style.css" />
  <link rel="stylesheet" href="../../css/admin/header.css" />
  <link rel="stylesheet" href="../../css/admin/pagiantion.css">
  <script src="../../JS/admin/table.js"></script>
</head>

<body>
  <?php
  // Start the session to ensure $_SESSION is available
  session_start();
  ?>
  <div class="container" id="container">

    <?php if (isset($_SESSION['user']['roleid']) && (string)$_SESSION['user']['roleid'] === '05'): ?>
      <h1>your role cannot access this page</h1>
      <a href="/sportswear-webstore/index.php">return to main page</a>
    <?php elseif (isset($_SESSION['user']['roleid']) && $_SESSION['user']['roleid'] !== '05'): ?>
    <?php include './includes/sidebar.php'; ?>
    <div class="main-content-area">
      <?php include './includes/header.php'; ?>
      <?php
      $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; // Default to dashboard
      $allowed_pages = ['dashboard', 'user', 'product', 'warehouse', 'orders', 'coupon', 'warranty', 'account', 'analytics', 'sales', 'notifications', 'settings'];

      if (in_array($page, $allowed_pages)) {
        include "./modules/$page.php"; // Include the corresponding module
      } else {
        echo "<h2>404 - Page not found</h2>"; // Handle invalid pages
      }
      ?>
    </div>
    <?php else: ?>
    <div class="login-required">
      <h1>Please log in to access the admin dashboard</h1>
      <p>You need to have administrator privileges to view this page.</p>
      <a href="/sportswear-webstore/index.php">return to main page</a>
    </div>
    <?php endif; ?>
  </div>
</body>

</html>