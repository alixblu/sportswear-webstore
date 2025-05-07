<?php
include __DIR__ . '/../controller/couponcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$couponController = new CouponController();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAllCoupons':
                $couponController->getAllCoupons();
                break;

            case 'getCouponById':
                $couponController->getCouponById();
                break;

            case 'getCouponByUserId':
                $userId = $_GET['id'] ?? null;
                if ($userId !== null) {
                    $couponController->getCouponByUserId($userId);
                } else {
                    echo "Thiếu userId.";
                }
                break;

            default:
                echo "Invalid GET action.";
        }
    } else {
        echo "Invalid GET request.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'createCoupon') {
        $name = $_POST['name'] ?? '';
        $percent = $_POST['percent'] ?? '';
        $duration = $_POST['duration'] ?? '';
        $couponController->createCoupon($name, $percent, $duration);
    } else {
        echo "Invalid POST request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateCoupon') {
        $id = $putData['id'] ?? null;
        $name = $putData['name'] ?? null;
        $percent = $putData['percent'] ?? null;
        $duration = $putData['duration'] ?? null;
        $status = $putData['status'] ?? null;

        if ($id !== null) {
            $couponController->updateCoupon($id, $name, $percent, $duration, $status);
        } else {
            echo "Missing couponId.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteCoupon' && isset($_GET['id'])) {
        $couponController->deleteCoupon($_GET['id']);
    } else {
        echo "Invalid DELETE request.";
    }
}
?>
