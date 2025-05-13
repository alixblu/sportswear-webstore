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
    // ==================================== GET for products ====================================
    if (isset($_GET['action']) && $_GET['action'] === 'getAllProducts') {
        $productController->getAllProducts();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getFilteredProducts') {
        $category = $_GET['category'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $status = $_GET['status'] ?? null;
        $min_price = $_GET['min_price'] ?? null;
        $max_price = $_GET['max_price'] ?? null;
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['newest', 'price_asc', 'price_desc', 'rating_desc']) ? $_GET['sort'] : 'newest';
        $search = $_GET['search'] ?? null;
        $productController->getFilteredProducts($category, $brand, $status, $min_price, $max_price, $sort, $search);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getFilteredProductsAdmin') {
        $page = $_GET['page'] ?? 1;
        $productsPerPage = $_GET['productsPerPage'] ?? 1;
        $search = $_GET['search'] ?? null;
        $category = $_GET['category'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $status = $_GET['status'] ?? null;
        $rating = $_GET['rating'] ?? null;

        $productController->getFilteredProductsAdmin($page, $productsPerPage, $search, $category, $brand, $status, $rating);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getProductById' && isset($_GET['id'])) {
        $productController->getProductById($_GET['id']);
    }
    // ==================================== GET for products's variants ====================================
    else if (isset($_GET['action']) && $_GET['action'] === 'getProductVariants' && isset($_GET['id'])) {
        $productController->getProductVariants($_GET['id']);
    }
    // ==================================== GET for brands ====================================
    else if (isset($_GET['action']) && $_GET['action'] === 'getAllBrands') {
        $productController->getAllBrands();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getBrandByName' && isset($_GET['name'])) {
        $productController->getBrandByName($_GET['name']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getBrandById' && isset($_GET['id'])) {
        $productController->getBrandById($_GET['id']);
    }
    // ==================================== GET for categories ====================================
    else if (isset($_GET['action']) && $_GET['action'] === 'getCategoryById' && isset($_GET['id'])) {
        $productController->getCategoryById($_GET['id']);
    } else if (isset($_GET['action']) && $_GET['action'] === 'getAllCategories') {
        $productController->getAllCategories();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getCategoryByName' && isset($_GET['name'])) {
        $productController->getCategoryByName($_GET['name']);
    }
    // ==================================== GET for discounts ====================================
    else if (isset($_GET['action']) && $_GET['action'] === 'getAllDiscounts') {
        $productController->getAllDiscounts();
    } else if (isset($_GET['action']) && $_GET['action'] === 'getDiscountByID' && isset($_GET['id'])) {
        $productController->getDiscountByID($_GET['id']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Yêu cầu GET không hợp lệ']);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    if ($_POST['action'] === 'uploadProductImage') {
        if (!$productId || !isset($_FILES['image'])) {
            echo json_encode([
                "status" => 400,
                "message" => "Thiếu ID sản phẩm hoặc file ảnh"
            ]);
            exit;
        }
        // Tạo thư mục nếu chưa tồn tại
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/sportswear-webstore/img/products/product" . $productId . "/";
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                echo json_encode([
                    "status" => 500,
                    "message" => "Không thể tạo thư mục lưu ảnh"
                ]);
                exit;
            }
        }
        // Upload ảnh
        $image = $_FILES['image'];
        $fileName = basename($image['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            echo json_encode([
                "status" => 200,
                "message" => "Hình ảnh đã được tải lên thành công"
            ]);
        } else {
            echo json_encode([
                "status" => 500,
                "message" => "Không thể di chuyển tệp đã tải lên"
            ]);
        }
    } else {
        echo json_encode([
            "status" => 400,
            "message" => "Không thể thực hiện POST"
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $putData = json_decode(file_get_contents("php://input"), true);
    file_put_contents('php://stderr', print_r($putData, true)); // hoặc log ra file

    if (!$putData) {
        echo json_encode(['error' => 'Dữ liệu JSON không hợp lệ !!']);
        exit;
    }
    $action = $_GET['action'] ?? null;
    $id = $_GET['id'] ?? null;

    if ($action === 'updateProduct' && $id !== null) {
        $productController->updateProduct($id, $putData);
    } else if ($action === 'updateProductStock' && $id !== null) {
        $productController->updateProductStock($id);
    }
    /*
    if (isset($putData['action']) && $putData['action'] === 'updateProduct' && isset($putData['ID'])) {
        $id = $putData['ID'];
        $data = [
            'categoryID' => $putData['categoryID'] ?? null,
            'discountID' => $putData['discountID'] ?? null,
            'brandID' => $putData['brandID'] ?? null,
            'name' => $putData['name'] ?? null,
            'markup_percentage' => $putData['markup_percentage'] ?? null,
            'rating' => $putData['rating'] ?? null,
            'image' => $putData['image'] ?? null,
            'description' => $putData['description'] ?? null,
            'stock' => $putData['stock'] ?? null,
            'status' => $putData['status'] ?? null
        ];
        $productController->updateProduct($id, $data);
    } else if (isset($putData['action']) && $putData['action'] === 'updateProductStock' && isset($putData['ID'])) {
        $productController->updateProductStock($putData['ID']);
    }
    */ else {
        echo json_encode(['error' => 'Yêu cầu PUT không hợp lệ']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteProduct' && isset($_GET['id'])) {
        $productController->deleteProduct($_GET['id']);
    } else {
        echo json_encode(['error' => 'Yêu cầu DELETE không hợp lệ']);
    }
}
