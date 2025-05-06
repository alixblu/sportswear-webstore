<?php
include __DIR__ . '/../controller/ordercontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$orderController = new OrderController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAllOrder':
                $orderController->getAllOrder();
                break;
            case 'getOrderById':
                if (isset($_GET['orderID'])) {
                    $orderController->getOrderById($_GET['orderID']);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Missing orderID parameter']);
                }
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid GET action']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing action parameter']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (isset($_GET['action']) && $_GET['action'] === 'updateOrderStatus') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['orderID']) && isset($data['status'])) {
            $orderController->updateOrderStatus($data['orderID'], $data['status']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing orderID or status parameter']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid PUT action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>