<?php
include __DIR__ . '/../controller/analyticscontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$analyticsController = new AnalyticsController();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        $startDate = $_GET['startDate'] ?? null;
        $endDate = $_GET['endDate'] ?? null;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;

        switch ($_GET['action']) {
            case 'getTopCustomers':
                if ($startDate !== null && $endDate !== null) {
                    $limit = $limit ?? 5;
                    $analyticsController->getTopCustomers($startDate, $endDate, $limit);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getTotalRevenue':
                if ($startDate !== null && $endDate !== null) {
                    $analyticsController->getTotalRevenue($startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getOrderStats':
                if ($startDate !== null && $endDate !== null) {
                    $analyticsController->getOrderStats($startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getTopProducts':
                if ($startDate !== null && $endDate !== null) {
                    $limit = $limit ?? 10;
                    $analyticsController->getTopProducts($startDate, $endDate, $limit);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getCouponUsage':
                if ($startDate !== null && $endDate !== null) {
                    $analyticsController->getCouponUsage($startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getActiveUsers':
                if ($startDate !== null && $endDate !== null) {
                    $analyticsController->getActiveUsers($startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing startDate or endDate']]);
                }
                break;

            case 'getCustomerOrderDetails':
                $userID = $_GET['userID'] ?? null;
                if ($userID !== null && $startDate !== null && $endDate !== null) {
                    $analyticsController->getCustomerOrderDetails($userID, $startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing userID, startDate, or endDate']]);
                }
                break;

            case 'getProductOrderDetails':
                $productID = $_GET['productID'] ?? null;
                if ($productID !== null && $startDate !== null && $endDate !== null) {
                    $analyticsController->getProductOrderDetails($productID, $startDate, $endDate);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'data' => ['error' => 'Missing productID, startDate, or endDate']]);
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['status' => 400, 'data' => ['error' => 'Invalid action']]);
                break;
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 400, 'data' => ['error' => 'Action parameter is required']]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 405, 'data' => ['error' => 'Method not allowed']]);
}
?>