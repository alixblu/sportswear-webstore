<?php
include dirname(__FILE__) . '/../controller/ordercontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$orderController = new OrderController();

// GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAllOrders':
                $orderController->getAllOrders();
                break;

            case 'getOrderDetails':
                $orderID = $_GET['id'] ?? null;
                if ($orderID !== null) {
                    $orderController->getOrderDetails($orderID);
                } else {
                    echo json_encode(['error' => 'Thiếu orderID.']);
                }
                break;

            case 'searchOrders':
                $orderID = $_GET['orderID'] ?? null;
                $customerName = $_GET['customerName'] ?? '';
                $fromDate = $_GET['fromDate'] ?? '';
                $toDate = $_GET['toDate'] ?? '';
                $orderController->searchOrders($orderID, $customerName, $fromDate, $toDate);
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Yêu cầu GET không hợp lệ']);
        }

    }
}

// PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateOrderStatus') {
        $orderID = $putData['orderID'] ?? null;
        $status = $putData['status'] ?? null;

        if ($id !== null) {
            $orderController->updateOrderStatus($orderID, $status);
            echo "Missing couponId.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}
