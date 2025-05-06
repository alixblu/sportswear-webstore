<?php
// Database connection
try {
    $conn = new PDO("mysql:host=localhost;dbname=sportswear;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die('<div class="error-message">Database connection error: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// Pagination setup
$items_per_page = 12; // Adjusted to match content.css grid (6 columns x 2 rows)
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Sanitize input function
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Build query conditions
$conditions = [];
$params = [];
$order_by = "";

// Handle search parameter
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = sanitize_input($_GET['search']);
    
    $brand_stmt = $conn->prepare("SELECT ID FROM brand WHERE LOWER(name) LIKE LOWER(:search)");
    $brand_stmt->bindValue(':search', "%$search%");
    $brand_stmt->execute();
    $brand = $brand_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($brand) {
        $conditions[] = "p.brandID = :brand";
        $params[':brand'] = $brand['ID'];
    } else {
        $category_stmt = $conn->prepare("SELECT ID FROM category WHERE LOWER(name) LIKE LOWER(:search)");
        $category_stmt->bindValue(':search', "%$search%");
        $category_stmt->execute();
        $category = $category_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            $conditions[] = "p.categoryID = :category";
            $params[':category'] = $category['ID'];
        } else {
            $conditions[] = "(b.name LIKE :search OR p.name LIKE :search OR p.description LIKE :search OR c.name LIKE :search)";
            $params[':search'] = "%$search%";
        }
    }
}

// Handle filter parameters
if (isset($_GET['brand']) && is_numeric($_GET['brand']) && $_GET['brand'] > 0) {
    $conditions[] = "p.brandID = :brand";
    $params[':brand'] = (int)$_GET['brand'];
}

if (isset($_GET['category']) && is_numeric($_GET['category']) && $_GET['category'] > 0) {
    $conditions[] = "p.categoryID = :category";
    $params[':category'] = (int)$_GET['category'];
}

if (!empty($_GET['status']) && in_array($_GET['status'], ['in_stock', 'out_of_stock'])) {
    $conditions[] = "p.status = :status";
    $params[':status'] = $_GET['status'];
}

if (!empty($_GET['rating'])) {
    $rating_range = explode('-', $_GET['rating']);
    if (count($rating_range) === 2 && is_numeric($rating_range[0]) && is_numeric($rating_range[1])) {
        $conditions[] = "COALESCE(r.avg_rating, 0) BETWEEN :rating_min AND :rating_max";
        $params[':rating_min'] = (float)$rating_range[0];
        $params[':rating_max'] = (float)$rating_range[1];
    }
}

if (!empty($_GET['price_start']) && is_numeric($_GET['price_start']) && 
    !empty($_GET['price_end']) && is_numeric($_GET['price_end'])) {
    $price_start = (float)$_GET['price_start'];
    $price_end = (float)$_GET['price_end'];
    if ($price_start <= $price_end) {
        $conditions[] = "(pv.price + (pv.price * p.markup_percentage / 100)) BETWEEN :price_start AND :price_end";
        $params[':price_start'] = $price_start;
        $params[':price_end'] = $price_end;
    }
}

// Sorting options
$valid_sorts = [
    'price_asc' => '(MIN(pv.price + (pv.price * p.markup_percentage / 100))) ASC',
    'price_desc' => '(MIN(pv.price + (pv.price * p.markup_percentage / 100))) DESC',
    'rating_asc' => 'COALESCE(r.avg_rating, 0) ASC',
    'rating_desc' => 'COALESCE(r.avg_rating, 0) DESC',
    'newest' => 'p.ID DESC'
];

$order_by = !empty($_GET['sort']) && isset($valid_sorts[$_GET['sort']]) ? 
            "ORDER BY " . $valid_sorts[$_GET['sort']] : "ORDER BY p.ID DESC";

$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

$select_clause = "
    p.ID, p.name, p.status, p.brandID, p.categoryID, p.markup_percentage,
    MIN(pv.price + (pv.price * p.markup_percentage / 100)) AS calculated_price,
    COALESCE(r.avg_rating, 0) AS rating,
    b.name AS brand_name,
    c.name AS category_name
";

// Count total products
$total_query = "
    SELECT COUNT(DISTINCT p.ID)
    FROM product p
    LEFT JOIN productvariant pv ON p.ID = pv.productID
    LEFT JOIN brand b ON p.brandID = b.ID
    LEFT JOIN category c ON p.categoryID = c.ID
    LEFT JOIN (
        SELECT productID, AVG(rating) AS avg_rating
        FROM review
        WHERE status = 'active'
        GROUP BY productID
    ) r ON p.ID = r.productID
    $where_clause
";

$stmt = $conn->prepare($total_query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$total_items = $stmt->fetchColumn();
$total_pages = max(1, ceil($total_items / $items_per_page));

// Fetch products
$query = "
    SELECT $select_clause
    FROM product p
    LEFT JOIN productvariant pv ON p.ID = pv.productID
    LEFT JOIN brand b ON p.brandID = b.ID
    LEFT JOIN category c ON p.categoryID = c.ID
    LEFT JOIN (
        SELECT productID, AVG(rating) AS avg_rating
        FROM review
        WHERE status = 'active'
        GROUP BY productID
    ) r ON p.ID = r.productID
    $where_clause
    GROUP BY p.ID, p.name, p.status, p.brandID, p.categoryID, p.markup_percentage, b.name, c.name
    $order_by
    LIMIT :offset, :items_per_page
";

$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch brands and categories for filters
$brands = $conn->query("SELECT ID, name FROM brand ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $conn->query("SELECT ID, name FROM category ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
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
        /* Additional styles for filter section and layout */
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
            background-color:rgb(15, 138, 220);
        }

        /* Adjust pagination to match content.css */
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

        /* Fix for removing underline from product card links */
        .product-card {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="product-section">
        <div class="section-header">
            <h1>
                <?= !empty($_GET['search']) ? 'Kết quả tìm kiếm: "' . htmlspecialchars($_GET['search']) . '"' : 
                    (!empty($_GET['brand']) ? 'Kết quả theo thương hiệu: ' . getBrandName($_GET['brand']) : 
                    (!empty($_GET['category']) ? 'Kết quả theo danh mục: ' . getCategoryName($_GET['category']) : 'Tất cả sản phẩm')) ?>
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
                        <label class="filter-label">Đánh giá</label>
                        <select class="filter-select" name="rating">
                            <option value="">Tất cả đánh giá</option>
                            <option value="4-5" <?= isset($_GET['rating']) && $_GET['rating'] === '4-5' ? 'selected' : '' ?>>4-5 sao</option>
                            <option value="3-4" <?= isset($_GET['rating']) && $_GET['rating'] === '3-4' ? 'selected' : '' ?>>3-4 sao</option>
                            <option value="2-3" <?= isset($_GET['rating']) && $_GET['rating'] === '2-3' ? 'selected' : '' ?>>2-3 sao</option>
                            <option value="1-2" <?= isset($_GET['rating']) && $_GET['rating'] === '1-2' ? 'selected' : '' ?>>1-2 sao</option>
                        </select>
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
                        <?php if (!in_array($key, ['brand', 'category', 'price_start', 'price_end', 'rating', 'status', 'sort', 'page']) && !empty($value)): ?>
                            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>

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
                    $rating = (float)$product['rating'];
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
                            <span class="current-price">$<?= number_format($product['calculated_price'], 2) ?></span>
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

    <?php
    function getBrandName($brandId) {
        global $conn;
        $stmt = $conn->prepare("SELECT name FROM brand WHERE ID = :id");
        $stmt->bindValue(':id', $brandId, PDO::PARAM_INT);
        $stmt->execute();
        $brand = $stmt->fetch(PDO::FETCH_ASSOC);
        return $brand ? htmlspecialchars($brand['name']) : 'Không xác định';
    }

    function getCategoryName($categoryId) {
        global $conn;
        $stmt = $conn->prepare("SELECT name FROM category WHERE ID = :id");
        $stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ? htmlspecialchars($category['name']) : 'Không xác định';
    }
    ?>
</body>
</html>