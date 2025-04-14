<?php
include __DIR__ . '/../controller/productcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$productController = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getAllProducts') {
        $productController->getAllProducts();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getProductById' && isset($_GET['id'])) {
        $productController->getProductById($_GET['id']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getProductVariants' && isset($_GET['id'])) {
        $productController->getProductVariants($_GET['id']);
    } else {
        echo json_encode(['error' => 'Invalid GET request']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    if (isset($putData['action']) && $putData['action'] === 'updateProduct' && isset($putData['id'])) {
        $id = $putData['id'];
        $data = [
            'categoryID' => $putData['categoryID'] ?? '',
            'discountID' => $putData['discountID'] ?? '',
            'brandID' => $putData['brandID'] ?? '',
            'name' => $putData['name'] ?? '',
            'markup_percentage' => $putData['markup_percentage'] ?? '',
            'rating' => $putData['rating'] ?? '',
            'image' => $putData['image'] ?? '',
            'description' => $putData['description'] ?? '',
            'stock' => $putData['stock'] ?? '',
            'status' => $putData['status'] ?? ''
        ];
        $productController->updateProduct($id, $data);
    } else if (isset($putData['action']) && $putData['action'] === 'updateProductStock' && isset($putData['id'])) {
        $productController->updateProductStock($putData['id']);
    } else {
        echo json_encode(['error' => 'Invalid PUT request']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteProduct' && isset($_GET['id'])) {
        $productController->deleteProduct($_GET['id']);
    } else {
        echo json_encode(['error' => 'Invalid DELETE request']);
    }
}
