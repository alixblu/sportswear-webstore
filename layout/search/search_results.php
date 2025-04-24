<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sportswear";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo '<div class="error-message">Kết nối thất bại: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

// Thiết lập phân trang
$items_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Khởi tạo điều kiện và tham số
$conditions = [];
$params = [];
$order_by = "";

// Hàm làm sạch đầu vào
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Xây dựng điều kiện truy vấn
if (!empty($_GET['search'])) {
    $search = sanitize_input($_GET['search']);
    $conditions[] = "p.name LIKE :search OR p.description LIKE :search";
    $params[':search'] = "%$search%";
}

if (!empty($_GET['brand']) && is_numeric($_GET['brand'])) {
    $conditions[] = "p.brandID = :brand";
    $params[':brand'] = (int)$_GET['brand'];
}

if (!empty($_GET['status']) && in_array($_GET['status'], ['in_stock', 'out_of_stock'])) {
    $conditions[] = "p.status = :status";
    $params[':status'] = $_GET['status'];
}

if (!empty($_GET['category']) && is_numeric($_GET['category'])) {
    $conditions[] = "p.categoryID = :category";
    $params[':category'] = (int)$_GET['category'];
}

// Bộ lọc rating
if (!empty($_GET['rating'])) {
    $rating_range = explode('-', $_GET['rating']);
    if (count($rating_range) === 2 && is_numeric($rating_range[0]) && is_numeric($rating_range[1])) {
        $conditions[] = "COALESCE(r.avg_rating, 0) BETWEEN :rating_min AND :rating_max";
        $params[':rating_min'] = (float)$rating_range[0];
        $params[':rating_max'] = (float)$rating_range[1];
    }
}

// Bộ lọc giá
if (!empty($_GET['price_start']) && !empty($_GET['price_end']) && is_numeric($_GET['price_start']) && is_numeric($_GET['price_end'])) {
    $price_start = (float)$_GET['price_start'];
    $price_end = (float)$_GET['price_end'];
    if ($price_start <= $price_end) {
        $conditions[] = "calculated_price BETWEEN :price_start AND :price_end";
        $params[':price_start'] = $price_start;
        $params[':price_end'] = $price_end;
    }
}

// Sắp xếp
$valid_sorts = [
    'price_asc' => 'calculated_price ASC',
    'price_desc' => 'calculated_price DESC',
    'rating_asc' => 'COALESCE(r.avg_rating, 0) ASC',
    'rating_desc' => 'COALESCE(r.avg_rating, 0) DESC'
];
if (!empty($_GET['sort']) && array_key_exists($_GET['sort'], $valid_sorts)) {
    $order_by = "ORDER BY " . $valid_sorts[$_GET['sort']];
}

// Tạo mệnh đề WHERE
$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Mệnh đề SELECT với giá tính toán và rating trung bình
$select_clause = "
    p.ID, p.name, p.image, p.status, p.brandID, p.categoryID, p.markup_percentage,
    MIN(pv.price + (pv.price * p.markup_percentage / 100)) AS calculated_price,
    COALESCE(r.avg_rating, 0) AS rating
";

// Truy vấn đếm tổng số sản phẩm
$total_query = "
    SELECT COUNT(DISTINCT p.ID)
    FROM product p
    LEFT JOIN productvariant pv ON p.ID = pv.productID
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

// Truy vấn lấy sản phẩm
$query = "
    SELECT $select_clause
    FROM product p
    LEFT JOIN productvariant pv ON p.ID = pv.productID
    LEFT JOIN (
        SELECT productID, AVG(rating) AS avg_rating
        FROM review
        WHERE status = 'active'
        GROUP BY productID
    ) r ON p.ID = r.productID
    $where_clause
    GROUP BY p.ID, p.name, p.image, p.status, p.brandID, p.categoryID, p.markup_percentage
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .search-results {
            max-width: 1200px;
            margin: 0 auto;
        }
        .result-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .result-header h2 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .result-header h2::before {
            content: '';
            display: inline-block;
            width: 5px;
            height: 24px;
            background-color: #ff0000;
            margin-right: 10px;
            vertical-align: middle;
        }
        .advanced-search {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .advanced-search .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }
        .advanced-search select, .advanced-search input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            flex: 1;
            min-width: 150px;
        }
        .advanced-search .found {
            font-size: 14px;
            color: #666;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .product-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .product-card h3 {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
        }
        .product-card .price {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .product-card .stars {
            color: #f5c518;
            margin: 5px 0;
        }
        .product-card .status {
            font-size: 14px;
            margin-top: 5px;
            color: #666;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination span.active {
            background-color: #000;
            color: #fff;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .error-message {
            color: #ff0000;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="search-results">
    <div class="result-header">
        <h2>Kết quả tìm kiếm</h2>
    </div>

    <!-- Form bộ lọc -->
    <div class="advanced-search">
        <form method="GET" action="">
            <div class="filter-row">
                <select name="sort">
                    <option value="">Sắp xếp</option>
                    <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                    <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                    <option value="rating_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_asc' ? 'selected' : '' ?>>Đánh giá: Thấp đến cao</option>
                    <option value="rating_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating_desc' ? 'selected' : '' ?>>Đánh giá: Cao đến thấp</option>
                </select>
                <select name="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="in_stock" <?= isset($_GET['status']) && $_GET['status'] === 'in_stock' ? 'selected' : '' ?>>Còn hàng</option>
                    <option value="out_of_stock" <?= isset($_GET['status']) && $_GET['status'] === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                </select>
                <select name="brand  brand">
                    <option value="">Tất cả thương hiệu</option>
                    <?php
                    $brand_stmt = $conn->query("SELECT ID, name FROM brand ORDER BY name");
                    while ($brand = $brand_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = isset($_GET['brand']) && $_GET['brand'] == $brand['ID'] ? 'selected' : '';
                        echo "<option value='{$brand['ID']}' $selected>" . htmlspecialchars($brand['name']) . "</option>";
                    }
                    ?>
                </select>
                <select name="category">
                    <option value="">Tất cả danh mục</option>
                    <?php
                    $category_stmt = $conn->query("SELECT ID, name FROM category ORDER BY name");
                    while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = isset($_GET['category']) && $_GET['category'] == $category['ID'] ? 'selected' : '';
                        echo "<option value='{$category['ID']}' $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
                <select name="rating">
                    <option value="">Tất cả đánh giá</option>
                    <?php
                    $ratings = [
                        '4-5' => '4-5 Sao',
                        '3-4' => '3-4 Sao',
                        '2-3' => '2-3 Sao',
                        '1-2' => '1-2 Sao',
                        '0-1' => '0-1 Sao'
                    ];
                    foreach ($ratings as $key => $label) {
                        $selected = isset($_GET['rating']) && $_GET['rating'] === $key ? 'selected' : '';
                        echo "<option value='$key' $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="filter-row">
                <input type="number" name="price_start" placeholder="Giá tối thiểu" min="0" step="0.01" value="<?= isset($_GET['price_start']) ? htmlspecialchars($_GET['price_start']) : '' ?>">
                <span>-</span>
                <input type="number" name="price_end" placeholder="Giá tối đa" min="0" step="0.01" value="<?= isset($_GET['price_end']) ? htmlspecialchars($_GET['price_end']) : '' ?>">
                <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" style="padding: 8px 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Áp dụng bộ lọc</button>
            </div>
        </form>
        <div class="found"><?= $total_items ?> sản phẩm được tìm thấy</div>
    </div>

    <!-- Lưới sản phẩm -->
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Sản phẩm') ?>">
                    <h3><?= htmlspecialchars($product['name'] ?? 'Sản phẩm không tên') ?></h3>
                    <div class="price">
                        $<?= number_format($product['calculated_price'] ?? 0, 2) ?>
                    </div>
                    <div class="stars">
                        <?php
                        $rating = (float)($product['rating'] ?? 0);
                        $full_stars = floor($rating);
                        $has_half_star = $rating - $full_stars >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $full_stars) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($has_half_star && $i == $full_stars + 1) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <div class="status">
                        <?= $product['status'] === 'in_stock' ? 'Còn hàng' : 'Hết hàng' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; text-align: center; color: #666;">Không tìm thấy sản phẩm phù hợp với tiêu chí của bạn.</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <?php
            $query_params = $_GET;
            for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++):
                $query_params['page'] = $i;
                $url = '?' . http_build_query($query_params);
            ?>
                <?php if ($i === $page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($url) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>