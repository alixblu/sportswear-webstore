<?php
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=localhost;dbname=sportswear;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    if (!empty($search)) {
        // Kiểm tra thương hiệu
        $stmt = $conn->prepare("SELECT ID FROM brand WHERE LOWER(name) LIKE LOWER(:search)");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        $brand = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($brand) {
            echo json_encode(['brandId' => $brand['ID'], 'categoryId' => null, 'no_results' => false]);
            exit;
        }
        
        // Kiểm tra danh mục
        $stmt = $conn->prepare("SELECT ID FROM category WHERE LOWER(name) LIKE LOWER(:search)");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            echo json_encode(['brandId' => null, 'categoryId' => $category['ID'], 'no_results' => false]);
            exit;
        }
        
        // Kiểm tra sản phẩm
        $stmt = $conn->prepare("
            SELECT ID FROM product 
            WHERE LOWER(name) LIKE LOWER(:search) 
            OR LOWER(description) LIKE LOWER(:search)
        ");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo json_encode(['brandId' => null, 'categoryId' => null, 'no_results' => false]);
            exit;
        }
        
        // Không tìm thấy kết quả
        echo json_encode(['brandId' => null, 'categoryId' => null, 'no_results' => true]);
    } else {
        // Từ khóa rỗng
        echo json_encode(['brandId' => null, 'categoryId' => null, 'no_results' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage(), 'no_results' => true]);
}
?>