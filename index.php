<!-- <?php
    session_start();
    include './src/config/exception/exceptionhandler.php';
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($requestUri === '/signup' && $method === 'GET') {
        include './layout/signup.php';
    }
    if ($requestUri === '/login' && $method === 'GET') {
        include './layout/login.php';
    }

    // user
    include './src/controller/usercontroller.php';
    $userController = new UserController();

    if ($requestUri === '/login' && $method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $userController->login( $username, $password);
    }
    if ($requestUri === '/signup' && $method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $phone = $data['phone'] ?? '';

        $userController->signup( $username, $password, $phone);
    }
?> -->


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/styles.css">

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <div class="wrapper">
        <?php include './layout/header.php'; ?>

        <?php //include './layout/main/login.php'; ?>

        <?php include './layout/footer.php'; ?>
    </div>


</body>
