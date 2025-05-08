<?php
session_start();
require_once __DIR__ . '/../../src/config/response/apiresponse.php';

// Hàm gọi API từ productrouter.php với xử lý lỗi
function callApi($url)
{
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

// Hàm lấy tên thương hiệu hoặc danh mục từ ID
function getNameFromId($id, $type)
{
    if (!is_numeric($id) || $id <= 0) {
        error_log("Invalid ID for $type: $id");
        return 'Không xác định';
    }
    $url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=get{$type}ById&id=" . urlencode($id);
    $data = callApi($url);
    if (!isset($data['status']) || $data['status'] !== 200 || !isset($data['data']) || !isset($data['data']['name'])) {
        error_log("Failed to get $type name for ID $id: " . ($data['message'] ?? 'No data returned'));
        return 'Không xác định';
    }
    return htmlspecialchars($data['data']['name']);
}

// Pagination setup
$items_per_page = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Xây dựng URL cho API getFilteredProducts
$api_params = [];
if (isset($_GET['brand']) && is_numeric($_GET['brand'])) {
    $api_params['brand'] = $_GET['brand'];
}
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $api_params['category'] = $_GET['category'];
}
if (!empty($_GET['status']) && in_array($_GET['status'], ['in_stock', 'out_of_stock'])) {
    $api_params['status'] = $_GET['status'];
}
if (!empty($_GET['price_start']) && is_numeric($_GET['price_start'])) {
    $api_params['min_price'] = $_GET['price_start'];
}
if (!empty($_GET['price_end']) && is_numeric($_GET['price_end'])) {
    $api_params['max_price'] = $_GET['price_end'];
}

// Xử lý tìm kiếm
$search_handled = false;
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = htmlspecialchars(trim($_GET['search']));

    // Add search term to API params for product name matching
    $api_params['search'] = $search;

    // Optionally check if the search term matches a brand name
    $brand_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getBrandByName&name=" . urlencode($search);
    $brand_data = callApi($brand_url);

    if (isset($brand_data['status']) && $brand_data['status'] === 200 && !empty($brand_data['data']) && isset($brand_data['data']['ID'])) {
        $api_params['brand'] = $brand_data['data']['ID'];
        $search_handled = true;
    }
}

// Sắp xếp
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['newest', 'price_asc', 'price_desc', 'rating_desc'])
    ? $_GET['sort'] : 'newest';
$api_params['sort'] = $sort;

// Gọi API để lấy sản phẩm
$api_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getFilteredProducts&" . http_build_query($api_params);
$product_data = callApi($api_url);
$products = (isset($product_data['status']) && $product_data['status'] === 200 && isset($product_data['data'])) ? $product_data['data'] : [];

// Tính toán phân trang
$total_items = count($products);
$total_pages = max(1, ceil($total_items / $items_per_page));

// Lấy danh sách thương hiệu và danh mục
$brands_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getAllBrands";
$categories_url = "http://localhost/sportswear-webstore/src/router/productrouter.php?action=getAllCategories";
$brands_data = callApi($brands_url);
$categories_data = callApi($categories_url);
$brands = (isset($brands_data['status']) && $brands_data['status'] === 200 && isset($brands_data['data'])) ? $brands_data['data'] : [];
$categories = (isset($categories_data['status']) && $categories_data['status'] === 200 && isset($categories_data['data'])) ? $categories_data['data'] : [];

// Cắt danh sách sản phẩm theo phân trang
$products = array_slice($products, $offset, $items_per_page);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm - SportsWear</title>
    <link rel="stylesheet" href="/sportswear-webstore/css/content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .filter-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        .filter-select,
        .filter-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.2);
        }

        .filter-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
            align-self: flex-end;
        }

        .filter-button:hover {
            background-color: rgb(15, 138, 220);
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
    <div class="product-section">
        <?php if (isset($product_data['status']) && $product_data['status'] !== 200): ?>
            <div class="error-message">
                Lỗi khi tải dữ liệu sản phẩm: <?= htmlspecialchars($product_data['message'] ?? 'Không rõ nguyên nhân') ?>
            </div>
        <?php endif; ?>

        <div class="section-header">
            <h1>
                <?= !empty($_GET['search']) ? 'Kết quả tìm kiếm: "' . htmlspecialchars($_GET['search']) . '"' : (!empty($_GET['brand']) ? 'Kết quả theo thương hiệu: ' . getNameFromId($_GET['brand'], 'Brand') : (!empty($_GET['category']) ? 'Kết quả theo danh mục: ' . getNameFromId($_GET['category'], 'Category') : 'Tất cả sản phẩm')) ?>
            </h1>
            <span class="search-count"><?= $total_items ?> sản phẩm được tìm thấy</span>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="/sportswear-webstore/layout/client/search_results.php">
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

                    <?php foreach ($_GET as $key => $value): ?>
                        <?php if (!in_array($key, ['brand', 'category', 'price_start', 'price_end', 'status', 'sort', 'page', 'search']) && !empty($value)): ?>
                            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>

                    <button type="submit" class="filter-button">Áp dụng bộ lọc</button>
                </div>
            </form>
        </div>

        <!-- Product List -->
        <div class="product-list">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product):
                    $image_path = "/sportswear-webstore/img/products/" . $product['ID'] . ".jpg";
                    $default_image = "/sportswear-webstore/img/products/default.jpg";
                    $image_src = file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path) ? $image_path : $default_image;
                    $rating = (float)($product['rating'] ?? 0);
                    $full_stars = floor($rating);
                    $has_half_star = $rating - $full_stars >= 0.5;
                ?>
                    <a href="/sportswear-webstore/layout/client/product_detail.php?id=<?= $product['ID'] ?>" class="product-card">
                        <?php if ($product['status'] === 'out_of_stock'): ?>
                            <div class="discount-badge">Hết hàng</div>
                        <?php endif; ?>

                        <div class="product-image">
                            <img src="<?= $image_src ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                                onerror="this.src='<?= $default_image ?>'">
                        </div>

                        <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>

                        <div class="product-price">
                            <span class="current-price">$<?= number_format($product['price'], 2) ?></span>
                        </div>

                        <div class="product-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $full_stars): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($has_half_star && $i == $full_stars + 1): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span>(<?= round($rating, 1) ?>)</span>
                        </div>

                        <button class="buy-button" <?= $product['status'] === 'out_of_stock' ? 'disabled' : '' ?>>
                            <i class="fas fa-shopping-cart"></i>
                            <?= $product['status'] === 'in_stock' ? 'Thêm vào giỏ' : 'Hết hàng' ?>
                        </button>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                $query_params = $_GET;
                if ($page > 1) {
                    $query_params['page'] = $page - 1;
                    $prev_url = '/sportswear-webstore/layout/client/search_results.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($prev_url) . '" class="page-link">« Trước</a>';
                }

                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    $query_params['page'] = 1;
                    $first_url = '/sportswear-webstore/layout/client/search_results.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($first_url) . '" class="page-link">1</a>';
                    if ($start_page > 2) echo '<span class="page-link">...</span>';
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $query_params['page'] = $i;
                    $page_url = '/sportswear-webstore/layout/client/search_results.php?' . http_build_query($query_params);
                    if ($i === $page) {
                        echo '<span class="page-link active">' . $i . '</span>';
                    } else {
                        echo '<a href="' . htmlspecialchars($page_url) . '" class="page-link">' . $i . '</a>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) echo '<span class="page-link">...</span>';
                    $query_params['page'] = $total_pages;
                    $last_url = '/sportswear-webstore/layout/client/search_results.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($last_url) . '" class="page-link">' . $total_pages . '</a>';
                }

                if ($page < $total_pages) {
                    $query_params['page'] = $page + 1;
                    $next_url = '/sportswear-webstore/layout/client/search_results.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($next_url) . '" class="page-link">Tiếp theo »</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>