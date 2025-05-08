<?php
session_start();
require_once __DIR__ . '/../../src/config/response/apiresponse.php';

// Hàm gọi API từ productrouter.php với xử lý lỗi
function callApi($url) {
    try {
        $response = @file_get_contents($url);
        if ($response === false) {
            error_log("Failed to fetch API: $url");
            return ['status' => 500, 'message' => 'Không thể kết nối đến API'];
        }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error for API $url: " . json_last_error_msg());
            return ['status' => 500, 'message' => 'Dữ liệu API không hợp lệ'];
        }
        return $data;
    } catch (Exception $e) {
        error_log("Error calling API $url: " . $e->getMessage());
        return ['status' => 500, 'message' => 'Lỗi khi gọi API: ' . $e->getMessage()];
    }
}

// Lấy danh sách thương hiệu và danh mục
$brands_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getAllBrands";
$categories_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getAllCategories";
$brands_data = callApi($brands_url);
$categories_data = callApi($categories_url);
$brands = (isset($brands_data['status']) && $brands_data['status'] === 200 && isset($brands_data['data'])) ? $brands_data['data'] : [];
$categories = (isset($categories_data['status']) && $categories_data['status'] === 200 && isset($categories_data['data'])) ? $categories_data['data'] : [];

// Thu thập tham số tìm kiếm ban đầu từ URL
$initial_params = [];
foreach ($_GET as $key => $value) {
    if (!empty($value)) {
        $initial_params[$key] = htmlspecialchars($value);
    }
}
$initial_params_json = json_encode($initial_params);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm - SportsWear</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="../../js/client/search.js" defer></script>
    <style>
        .page-container {
            margin-top: 40px;
            padding: 20px;
            min-height: calc(100vh - 300px);
        }
        .filter-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1d3557;
            font-size: 14px;
        }
        .filter-select, .filter-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.3s;
        }
        .filter-select:focus, .filter-input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.2);
        }
        
        .pagination {
            margin-top: 3rem;
        }
        .no-results {
            text-align: center;
            padding: 50px 0;
            color: #666;
            grid-column: 1 / -1;
        }
        .no-results i {
            font-size: 50px;
            color: #ddd;
            margin-bottom: 20px;
        }
        .no-results h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .product-card {
            text-decoration: none;
        }
        .error-message {
            text-align: center;
            padding: 20px;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <div class="page-container">
        <div class="product-section">
            <div class="section-header">
                <h1>Đang tải...</h1>
                <span class="search-count">0 sản phẩm được tìm thấy</span>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form>
                    <div class="filter-form">
                        <div class="filter-group">
                            <label class="filter-label">Thương hiệu</label>
                            <select class="filter-select" name="brand">
                                <option value="">Tất cả thương hiệu</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['ID'] ?>" <?= isset($_GET['brand']) && $_GET['brand'] == $brand['ID'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Danh mục</label>
                            <select class="filter-select" name="category">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['ID'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['ID'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Khoảng giá</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="number" class="filter-input" name="price_start" placeholder="Từ" 
                                       value="<?= isset($_GET['price_start']) ? htmlspecialchars($_GET['price_start']) : '' ?>">
                                <input type="number" class="filter-input" name="price_end" placeholder="Đến" 
                                       value="<?= isset($_GET['price_end']) ? htmlspecialchars($_GET['price_end']) : '' ?>">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Trạng thái</label>
                            <select class="filter-select" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="in_stock" <?= isset($_GET['status']) && $_GET['status'] === 'in_stock' ? 'selected' : '' ?>>Còn hàng</option>
                                <option value="out_of_stock" <?= isset($_GET['status']) && $_GET['status'] === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Sắp xếp theo</label>
                            <select class="filter-select" name="sort">
                                <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                                <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                                <option value="rating_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_desc' ? 'selected' : '' ?>>Đánh giá cao nhất</option>
                            </select>
                        </div>

                        <?php if (!empty($_GET['search'])): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                        <?php endif; ?>

                        
                    </div>
                </form>
            </div>

            <!-- Product List -->
            <div class="product-list">
                <div class="no-results">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>Đang tải sản phẩm...</h3>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination"></div>
        </div>
    </div>

    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        // Khởi tạo tìm kiếm ban đầu dựa trên tham số URL
        window.addEventListener('DOMContentLoaded', () => {
            const initialParams = <?php echo $initial_params_json; ?>;
            if (Object.keys(initialParams).length > 0) {
                updateResults(initialParams);
            }
        });
    </script>
</body>
</html>