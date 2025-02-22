<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div style=" display: flex; flex-direction: column; min-height: 100vh;">
        <?php include './layout/header.php'?>
        <div style="flex:1;">
            <?php
                $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $method = $_SERVER['REQUEST_METHOD'];

                if ($requestUri === '/signup' && $method === 'GET') {
                    include './layout/signup.php';
                }
                if ($requestUri === '/login' && $method === 'GET') {
                    include './layout/login.php';
                }
            ?>
        </div>
        <?php include './layout/footer.php'?>
    </div>
</body>
</html>