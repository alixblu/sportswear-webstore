<?php
include __DIR__ . '/../controller/usercontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if (isset($_GET['action']) && $_GET['action'] === 'getAccountByUserId' && isset($_GET['userId'])) {
        $userController->getAccountByUserId($_GET['userId']);
    }
    else if (isset($_GET['action']) && $_GET['action'] === 'getAllUsers') {
        $userController->getAllUsers();
    }else if (isset($_GET['action']) && $_GET['action'] === 'getAllRoles') {
        $userController->getAllRoles();
    }else if (isset($_GET['action']) && $_GET['action'] === 'exportFile') {
        $userController->exportExcel();
    }else if (isset($_GET['action']) && $_GET['action'] === 'search') {
        $keyword = $_GET['keyword'];
        $fields = $_GET['fields'];
        $results = $userController->search($keyword, $fields);
    } else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'defaultAccount') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $roleID = $_POST['roleID'] ?? '';
        $userController->defaultAccount($name, $email, $phone, $gender, $roleID);
    } else if (isset($_GET['action']) && $_GET['action'] === 'uploadFile'){
        $file = $_FILES['excel_file']['tmp_name'];
        $userController->importExcel($file);
    }
    else {
        echo "Invalid POST request.";
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
        $email = $putData['email'] ?? '';
        $passWord = $putData['passWord'] ?? '';
        $phone = $putData['phone'] ?? '';
        $gender = $putData['gender'] ?? '';
        $roleID = $putData['roleID'] ?? '';

        if ($id !== null) {
            $userController->updateUsers($id, $name, $email, $passWord, $phone, $gender, $roleID);
        } else {
            echo "Thiếu userId.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}

?>

