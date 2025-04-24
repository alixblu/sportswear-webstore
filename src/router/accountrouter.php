<?php
// File: src/router/accountRouter.php
require_once __DIR__ . '/../controller/accountcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new AccountController();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? '';
        if (empty($action)) {
            // Hiển thị trang quản lý tài khoản
            define('ROOT_PATH', dirname(__DIR__));
            $controller->renderAccountPage();
            exit;
        }
        switch ($action) {
            case 'getAccountDetails':
                if (isset($_GET['accountId'])) {
                    $controller->getAccountDetails($_GET['accountId']);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu tham số accountId'], JSON_UNESCAPED_UNICODE);
                }
                break;
            case 'getPermissions':
                if (isset($_GET['roleId'])) {
                    $controller->getPermissions($_GET['roleId']);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu tham số roleId'], JSON_UNESCAPED_UNICODE);
                }
                break;
            case 'getAllModules':
                $controller->getAllModules();
                break;
            case 'getAllRoles':
                $controller->getAllRoles();
                break;
            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['action'])) {
            echo json_encode(['status' => 400, 'message' => 'Thiếu hành động'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        switch ($input['action']) {
            case 'updateAccount':
                $controller->updateAccount($input);
                break;
            case 'updatePermissions':
                $controller->updatePermissions($input);
                break;
            case 'filterAccounts':
                $controller->filterAccounts($input);
                break;
            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['status' => 405, 'message' => 'Phương thức không được hỗ trợ'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 500, 'message' => 'Lỗi server: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>