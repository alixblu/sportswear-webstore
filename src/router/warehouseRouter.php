<?php
include __DIR__ . '/../controller/warehousecontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$warehouseController = new WarehouseController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['action']) && $_GET['action'] === 'getAccountByUserId' && isset($_GET['userId'])) {
        $userController->getAccountByUserId($_GET['userId']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getAllUsers') {
        $userController->getAllUsers();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getAllRoles') {
        $userController->getAllRoles();
    } else if (isset($_GET['action']) && $_GET['action'] === 'exportFile') {
        $userController->exportExcel();
    } else if (isset($_GET['action']) && $_GET['action'] === 'search') {
        $keyword = $_GET['keyword'];
        $fields = $_GET['fields'];
        $results = $userController->search($keyword, $fields);
    } else {
        echo "Invalid GET request.";
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

            default:
                echo json_encode(['status' => 400, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Yêu cầu POST không hợp lệ'], JSON_UNESCAPED_UNICODE);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteUsers' && isset($_GET['userId'])) {
        $userController->deleteUsers($_GET['userId']);
    } else {
        echo "Invalid DELETE request.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateUsers') {
        $id = $putData['userId'] ?? null;
        $name = $putData['name'] ?? '';
        $address = $putData['address'] ?? '';
        $email = $putData['email'] ?? '';
        $passWord = $putData['passWord'] ?? '';
        $phone = $putData['phone'] ?? '';
        $gender = $putData['gender'] ?? '';
        $roleID = $putData['roleID'] ?? '';

        if ($id !== null) {
            $userController->updateUsers($id, $name, $address, $email, $passWord, $phone, $gender, $roleID);
        } else {
            echo "Thiếu userId.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}
