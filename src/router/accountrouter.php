<?php
require_once __DIR__ . '/../controller/accountController.php';
require_once __DIR__ . '/../controller/authcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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
                if ($accountId !== null) {
                    $controller->getAccountById($accountId);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu accountId'], JSON_UNESCAPED_UNICODE);
                }
                break;
            case 'getPermissions':
                $roleId = $_GET['roleId'] ?? null;
                if ($roleId !== null) {
                    $controller->getPermissions($roleId);
                } else {
                    echo json_encode(['status' => 400, 'message' => 'Thiếu roleId'], JSON_UNESCAPED_UNICODE);
                }
                break;
            case 'getAllModules':
                $controller->getAllModules();
                break;
            case 'getAllRoles':
                $controller->getAllRoles();
                break;
            case 'info':
                $authController->info();
                break;
            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Yêu cầu GET không hợp lệ'], JSON_UNESCAPED_UNICODE);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'createAccount':
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
                $controller->createAccount($username, $password, $fullname, $phone, $roleId, $status, $email, $address, $gender, $dateOfBirth);
                break;
            case 'filterAccounts':
                $controller->filterAccounts($input);
                break;

            case 'updatePassword':
                $currentPassword = $input['currentPassword'] ?? '';
                $newPassword = $input['newPassword'] ?? '';
                $authController->updatePassword($currentPassword, $newPassword);
                break;
            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Yêu cầu POST không hợp lệ'], JSON_UNESCAPED_UNICODE);
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
    
                $authController->updateUserLogin($name, $address, $birth,$phone,$gender);
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
        $controller->deleteAccount($_GET['accountId']);
    } else {
        echo json_encode(['status' => 400, 'message' => 'Yêu cầu DELETE không hợp lệ'], JSON_UNESCAPED_UNICODE);
    }
}
?>