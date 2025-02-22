<?php
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
?>