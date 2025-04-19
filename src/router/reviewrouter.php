<?php
include __DIR__ . '/../controller/reviewcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$reviewController = new ReviewController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getAllReviews') {
        $reviewController->getAllReviews();
    }  else {
        echo "Invalid GET request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'createReview') {

        $userId = $_POST['userId'] ?? '';
        $productId = $_POST['productId'] ?? '';
        $rating = $_POST['rating'] ?? '';
        $commentContent = $_POST['commentContent'] ?? '';
        $reviewController->createReview($userId, $productId, $rating, $commentContent);
    } else {
        echo "Invalid POST request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateReview') {
        $reviewId = $putData['reviewId'] ?? null;
        $rating = $putData['rating'] ?? null;
        $commentContent = $putData['commentContent'] ?? null;

        if ($reviewId !== null) {
            $reviewController->updateReview($reviewId, $rating, $commentContent);
        } else {
            echo "Missing reviewId.";
        }
    } else {
        echo "Invalid PUT request.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteReview' && isset($_GET['reviewId'])) {
        $reviewController->deleteReview($_GET['reviewId']);
    } else {
        echo "Invalid DELETE request.";
    }
}
?>
