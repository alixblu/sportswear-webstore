<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Sportwear Store</title>

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="./css/content.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/profile.css">
</head>

<body>
    <?php include './layout/header.php'; ?>

    <div class="page-container">
        <?php
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            include './layout/client/search_results.php';
        } else if (isset($_GET['page']) && $_GET['page'] === 'profile') {
            include './layout/client/profile/index.php';
        } else {
            include './layout/content.php';
        }
        ?>
    </div>

    <?php include './layout/footer.php'; ?>
</body>

</html>