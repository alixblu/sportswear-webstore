<?php
include __DIR__ . '/../controller/cartcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$cartController = new CartController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getCartByUserId') {
        $userId = $_GET['userId'] ?? null;
        if ($userId !== null) {
            $cartController->getCartByUserId($userId);
        } else {
            echo "Thiếu userId.";
        }
    } else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'createCart') {
        $userId = $_POST['userId'] ?? null;
        if ($userId !== null) {
            $cartController->createCart($userId);
        } else {
            echo "Thiếu userId.";
        }
    } else {
        echo "Invalid POST request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateTotalPrice') {
        $cartId = $putData['cartId'] ?? null;
        $totalPrice = $putData['totalPrice'] ?? null;

        if ($cartId !== null && $totalPrice !== null) {
            $cartController->updateTotalPrice($cartId, $totalPrice);
        } else {
            echo "Thiếu cartId hoặc totalPrice.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}
