<?php
include __DIR__ . '/../controller/analyticscontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$analyticsController = new AnalyticsController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getTotalRevenue':
                $startDate = $_GET['startDate'] ?? null;
                $endDate = $_GET['endDate'] ?? null;
                if ($startDate && $endDate) {
                    $analyticsController->getTotalRevenue($startDate, $endDate);
                } else {
                    echo "Missing startDate or endDate.";
                }
                break;

            case 'getOrderStats':
                $startDate = $_GET['startDate'] ?? null;
                $endDate = $_GET['endDate'] ?? null;
                if ($startDate && $endDate) {
                    $analyticsController->getOrderStats($startDate, $endDate);
                } else {
                    echo "Missing startDate or endDate.";
                }
                break;

            case 'getTopProducts':
                $startDate = $_GET['startDate'] ?? null;
                $endDate = $_GET['endDate'] ?? null;
                $limit = $_GET['limit'] ?? 10;
                if ($startDate && $endDate) {
                    $analyticsController->getTopProducts($startDate, $endDate, $limit);
                } else {
                    echo "Missing startDate or endDate.";
                }
                break;

            case 'getCouponUsage':
                $startDate = $_GET['startDate'] ?? null;
                $endDate = $_GET['endDate'] ?? null;
                if ($startDate && $endDate) {
                    $analyticsController->getCouponUsage($startDate, $endDate);
                } else {
                    echo "Missing startDate or endDate.";
                }
                break;

            default:
                echo "Invalid GET action.";
        }
    } else {
        echo "Invalid GET request.";
    }
}
?>