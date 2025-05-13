<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__FILE__) . '/../controller/accountcontroller.php';
require_once __DIR__ . '/../controller/authcontroller.php';

$controller = new AccountController();
$authController = new AuthController();

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
            case 'info':
                $authController->info();
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

            case 'createRole':
                if (!isset($data['name'], $data['moduleIds']) || !is_array($data['moduleIds'])) {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu tên vai trò hoặc danh sách moduleIds không hợp lệ']);
                    exit;
                }
                $controller->createRole($data['name'], $data['moduleIds']);
                break;

            case 'updatePassword':
                $currentPassword = $data['passwordOld'] ?? '';
                $newPassword = $data['newPassword'] ?? '';
                $authController->updatePassword($currentPassword, $newPassword);
                break;

            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động POST không hợp lệ']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Thiếu tham số action']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'updateAccount':
                $accountId = $input['accountId'] ?? null;
                $username = $input['username'] ?? '';
                $password = $input['password'] ?? '';
                $fullname = $input['fullname'] ?? '';
                $phone = $input['phone'] ?? '';
                $roleId = $input['roleId'] ?? '';
                $status = $input['status'] ?? '';
                $email = $input['email'] ?? null;
                $address = $input['address'] ?? null;
                $gender = $input['gender'] ?? null;
                $dateOfBirth = $input['dateOfBirth'] ?? null;

                if ($accountId !== null) {
                    $controller->updateAccount($accountId, $username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu accountId'], JSON_UNESCAPED_UNICODE);
                }
                break;

            case 'updateUserLogin':
                $name = $input['name'] ?? '';
                $address = $input['address'] ?? '';
                $birth = $input['birth'] ?? '';
                $phone = $input['phone'] ?? '';
                $gender = $input['gender'] ?? '';

                $authController->updateUserLogin($name, $address, $birth, $phone, $gender);
                break;

            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Yêu cầu PUT không hợp lệ'], JSON_UNESCAPED_UNICODE);
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