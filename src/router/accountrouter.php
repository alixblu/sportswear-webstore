<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__FILE__) . '/../controller/accountcontroller.php';

$controller = new AccountController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAllAccounts':
                $controller->getAllAccounts();
                break;

            case 'getAccountById':
                $accountId = $_GET['accountId'] ?? null;
                if ($accountId !== null && is_numeric($accountId)) {
                    $controller->getAccountById((int)$accountId);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu hoặc accountId không hợp lệ']);
                }
                break;

            case 'getAllRoles':
                $controller->getAllRoles();
                break;

            case 'getPermissions':
                $roleId = $_GET['roleId'] ?? null;
                if ($roleId !== null && is_numeric($roleId)) {
                    $controller->getPermissions((int)$roleId);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu hoặc roleId không hợp lệ']);
                }
                break;

            case 'getAllModules':
                $controller->getAllModules();
                break;

            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động GET không hợp lệ']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Thiếu tham số action']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'createAccount':
                if (!isset($data['username'], $data['password'], $data['fullname'], $data['phone'], $data['roleId'], $data['status'])) {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu các trường bắt buộc']);
                    exit;
                }
                $controller->createAccount(
                    $data['username'],
                    $data['password'],
                    $data['fullname'],
                    $data['phone'],
                    $data['roleId'],
                    $data['status'],
                    $data['email'] ?? null,
                    $data['address'] ?? null,
                    $data['gender'] ?? null,
                    $data['dateOfBirth'] ?? null
                );
                break;

            case 'filterAccounts':
                if (!isset($data['type'])) {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu loại tài khoản']);
                    exit;
                }
                $filters = [
                    'type' => $data['type'],
                    'status' => $data['status'] ?? 'all',
                    'roleID' => $data['roleID'] ?? 'all'
                ];
                $controller->filterAccounts($filters);
                break;

            case 'updatePermissions':
                if (!isset($data['roleId'], $data['moduleIds']) || !is_array($data['moduleIds'])) {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu roleId hoặc danh sách moduleIds không hợp lệ']);
                    exit;
                }
                $controller->updatePermissions($data['roleId'], $data['moduleIds']);
                break;

            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động POST không hợp lệ']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Thiếu tham số action']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action']) && $data['action'] === 'updateAccount') {
        if (!isset($data['accountId'], $data['username'], $data['fullname'], $data['phone'], $data['roleId'], $data['status'])) {
            echo json_encode(['status' => 400, 'message' => 'Thiếu các trường bắt buộc']);
            exit;
        }
        $controller->updateAccount(
            $data['accountId'],
            $data['username'],
            $data['password'] ?? null,
            $data['fullname'],
            $data['phone'],
            $data['roleId'],
            $data['status'],
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['gender'] ?? null,
            $data['dateOfBirth'] ?? null
        );
    } else {
        echo json_encode(['status' => 400, 'message' => 'Hành động PUT không hợp lệ']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteAccount' && isset($_GET['accountId'])) {
        $accountId = $_GET['accountId'];
        if (is_numeric($accountId)) {
            $controller->deleteAccount((int)$accountId);
        } else {
            echo json_encode(['status' => 400, 'message' => 'accountId không hợp lệ']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Hành động DELETE không hợp lệ']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>