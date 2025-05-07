<?php
include __DIR__ . '/../controller/cartdetailcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$cartDetailController = new CartDetailController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getCartDetailsByCartID') {
        $cartID = $_GET['cartID'] ?? null;
        if ($cartID !== null) {
            $cartDetailController->getCartDetailsByCartID($cartID);
        } else {
            echo "Thiếu cartID.";
        }
    } else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateCartDetailQuantity') {
        $cartDetailID = $putData['cartDetailID'] ?? null;
        $quantity = $putData['quantity'] ?? null;

        if ($cartDetailID !== null && $quantity !== null) {
            $cartDetailController->updateCartDetailQuantity($cartDetailID, $quantity);
        } else {
            echo "Thiếu cartDetailID hoặc quantity.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteData);

    if (isset($deleteData['action']) && $deleteData['action'] === 'deleteCartDetail') {
        $cartDetailID = $deleteData['cartDetailID'] ?? null;

        if ($cartDetailID !== null) {
            $cartDetailController->deleteCartDetail($cartDetailID);
        } else {
            echo "Thiếu cartDetailID.";
        }
    } else {
        echo "Invalid DELETE request.";
    }
}
