<?php
// SearchRouter.php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    exit();
}

require_once dirname(__FILE__) . '/../controller/searchcontroller.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$searchController = new SearchController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'searchProducts') {
        $category = $_GET['category'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $status = $_GET['status'] ?? null;
        $min_price = $_GET['min_price'] ?? null;
        $max_price = $_GET['max_price'] ?? null;
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['newest', 'price_asc', 'price_desc', 'rating_desc']) ? $_GET['sort'] : 'newest';
        $search = $_GET['search'] ?? null;
        $searchController->searchProducts($category, $brand, $status, $min_price, $max_price, $sort, $search);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getAllBrands') {
        $searchController->getAllBrands();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getAllCategories') {
        $searchController->getAllCategories();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getBrandByName' && isset($_GET['name'])) {
        $searchController->getBrandByName($_GET['name']);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getCategoryByName' && isset($_GET['name'])) {
        $searchController->getCategoryByName($_GET['name']);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getBrandById' && isset($_GET['id'])) {
        $searchController->getBrandById($_GET['id']);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getCategoryById' && isset($_GET['id'])) {
        $searchController->getCategoryById($_GET['id']);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'searchByName' && isset($_GET['name'])) {
    $searchController->searchByName($_GET['name']);
    }
     else {
        http_response_code(400);
        echo json_encode(['error' => 'Yêu cầu GET không hợp lệ']);
    }
}