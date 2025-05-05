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
  <script src="../../JS/admin/table.js"></script>
  <script src="../../JS/admin/sidebar.js" defer></script>

</head>

<body>
  <div class="container">
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
  </div>
</body>

</html>