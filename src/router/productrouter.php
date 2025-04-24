<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    header('HTTP/1.1 204 No Content');
    exit();
}

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
    } else if (isset($_GET['action']) && $_GET['action'] === 'getCategoryById' && isset($_GET['id'])) {
        $productController->getCategoryById($_GET['id']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getBrandById' && isset($_GET['id'])) {
        $productController->getBrandById($_GET['id']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getAllCategories') {
        $productController->getAllCategories();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getAllBrands') {
        $productController->getAllBrands();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getBrandByName' && isset($_GET['name'])) {
        $productController->getBrandByName($_GET['name']);
    }else if (isset($_GET['action']) && $_GET['action'] === 'getCategoryByName' && isset($_GET['name'])) {
        $productController->getCategoryByName($_GET['name']);
    } else {
        echo json_encode(['error' => 'Invalid GET request']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'uploadProductImage') {
    $productId = $_POST['product_id'];
    if (isset($_FILES['image']) && $productId) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/sportwear/img/products/";
        $targetFile = $targetDir . "product_" . $productId . ".png";

        // Remove old file if exists
        if (file_exists($targetFile)) {
            unlink($targetFile);
        }

        // Move uploaded file (force .png extension)
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo json_encode([
                "status" => 200,
                "message" => "Image uploaded successfully"
            ]);
        } else {
            echo json_encode([
                "status" => 500,
                "message" => "Failed to move uploaded file"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 400,
            "message" => "No image or product ID provided"
        ]);
    }
    exit;
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
