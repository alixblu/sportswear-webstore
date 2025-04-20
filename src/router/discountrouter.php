<?php
include __DIR__ . '/../controller/discountcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$discountController = new DiscountController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAllDiscounts':
                $discountController->getAllDiscounts();
                break;

            case 'getDiscountById':
                $id = $_GET['id'] ?? null;
                if ($id !== null) {
                    $discountController->getDiscountById($id);
                } else {
                    echo "Thiếu ID.";
                }
                break;

            default:
                echo "Invalid GET action.";
        }
    } else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'createDiscount') {
        $name = $_POST['name'] ?? '';
        $discountRate = $_POST['discountRate'] ?? '';
        $status = $_POST['status'] ?? '';

        $discountController->createDiscount($name, $discountRate, $status);
    } else {
        echo "Invalid POST request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateDiscount') {
        $id = $putData['id'] ?? null;
        $name = $putData['name'] ?? '';
        $discountRate = $putData['discountRate'] ?? '';
        $status = $putData['status'] ?? '';

        if ($id !== null) {
            $discountController->updateDiscount($id, $name, $discountRate, $status);
        } else {
            echo "Thiếu ID.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteDiscount' && isset($_GET['id'])) {
        $discountController->deleteDiscount($_GET['id']);
    } else {
        echo "Invalid DELETE request.";
    }
}
