<?php
header('Content-Type: application/json');
try {
    $conn = new PDO("mysql:host=localhost;dbname=sportswear;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    if (!empty($search)) {
        $stmt = $conn->prepare("SELECT ID FROM brand WHERE LOWER(name) LIKE LOWER(:search)");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        $brand = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($brand) {
            echo json_encode(['brandId' => $brand['ID'], 'categoryId' => null]);
            exit;
        }
        
        $stmt = $conn->prepare("SELECT ID FROM category WHERE LOWER(name) LIKE LOWER(:search)");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            echo json_encode(['brandId' => null, 'categoryId' => $category['ID']]);
            exit;
        }
        
        echo json_encode(['brandId' => null, 'categoryId' => null]);
    } else {
        echo json_encode(['brandId' => null, 'categoryId' => null]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>