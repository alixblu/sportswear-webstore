<?php
require_once dirname(__FILE__) . '/../service/productservice.php';

try {
    $productRepo = new ProductService();



    // Test findById
    echo "\nFetching product with ID 1:\n";
    $product = $productRepo->getProductById(1); // Replace 1 with an actual ID from your database
    header('Content-Type: application/json');
    echo json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>