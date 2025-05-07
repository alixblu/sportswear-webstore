<?php
include __DIR__ . '/../controller/cartcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$cartController = new CartController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getCartByUserId') {
        $cartController->getCartByUserId();
    } else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'createCart':
            $userId = $_POST['userId'] ?? null;
            if ($userId !== null) {
                $cartController->createCart($userId);
            } else {
                echo "Thiếu userId.";
            }
            break;

        case 'addCartDetail':
            $productID = $_POST['productID'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if ($productID !== null && $quantity !== null) {
                $cartController->addProductCart($productID, $quantity);
            } else {
                echo "Thiếu productID, quantity hoặc cartID.";
            }
            break;

        default:
            echo "Invalid POST request.";
            break;
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
