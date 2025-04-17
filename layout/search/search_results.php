<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sportswear";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo '<div class="error-message">Connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

// Pagination settings
$items_per_page = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Initialize query conditions and parameters
$conditions = [];
$params = [];
$order_by = "";

// Build query conditions based on filters
if (!empty($_GET['search'])) {
    $conditions[] = "p.name LIKE :search";
    $params[':search'] = "%" . trim($_GET['search']) . "%";
}
if (!empty($_GET['brand']) && is_numeric($_GET['brand'])) {
    $conditions[] = "p.brandID = :brand";
    $params[':brand'] = (int)$_GET['brand'];
}
if (!empty($_GET['status']) && in_array($_GET['status'], ['in_stock', 'out_of_stock'])) {
    $conditions[] = "p.status = :status";
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['rating'])) {
    $rating_range = explode('-', $_GET['rating']);
    if (count($rating_range) === 2 && is_numeric($rating_range[0]) && is_numeric($rating_range[1])) {
        $conditions[] = "p.rating BETWEEN :rating_min AND :rating_max";
        $params[':rating_min'] = (float)$rating_range[0];
        $params[':rating_max'] = (float)$rating_range[1];
    }
}
if (!empty($_GET['category']) && is_numeric($_GET['category'])) {
    $conditions[] = "p.categoryID = :category";
    $params[':category'] = (int)$_GET['category'];
}
if (!empty($_GET['price_start']) && !empty($_GET['price_end']) && is_numeric($_GET['price_start']) && is_numeric($_GET['price_end'])) {
    $conditions[] = "calculated_price BETWEEN :price_start AND :price_end";
    $params[':price_start'] = (float)$_GET['price_start'];
    $params[':price_end'] = (float)$_GET['price_end'];
}

// Sorting
if (!empty($_GET['sort']) && in_array($_GET['sort'], ['price_asc', 'price_desc'])) {
    $order_by = $_GET['sort'] === 'price_asc' ? "ORDER BY calculated_price ASC" : "ORDER BY calculated_price DESC";
}

// Construct WHERE clause
$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Select clause with calculated price
$select_clause = "p.*, (100 * (1 + p.markup_percentage/100)) AS calculated_price";

// Count total items
$total_query = "SELECT COUNT(*) FROM product p $where_clause";
$stmt = $conn->prepare($total_query);
$stmt->execute($params);
$total_items = $stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch products
$query = "SELECT $select_clause FROM product p $where_clause $order_by LIMIT :offset, :items_per_page";
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="search-results">
    <div class="result-header">
        <h2>Search Results</h2>
    </div>

    <!-- Filters Form -->
    <div class="advanced-search">
        <form method="GET" action="">
            <div class="filter-row">
                <select name="sort">
                    <option value="">Sort</option>
                    <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                </select>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="in_stock" <?= isset($_GET['status']) && $_GET['status'] === 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                    <option value="out_of_stock" <?= isset($_GET['status']) && $_GET['status'] === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                </select>
                <select name="brand">
                    <option value="">All Brands</option>
                    <?php
                    $brand_stmt = $conn->query("SELECT * FROM brand ORDER BY name");
                    while ($brand = $brand_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = isset($_GET['brand']) && $_GET['brand'] == $brand['ID'] ? 'selected' : '';
                        echo "<option value='{$brand['ID']}' $selected>" . htmlspecialchars($brand['name']) . "</option>";
                    }
                    ?>
                </select>
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    $category_stmt = $conn->query("SELECT * FROM category ORDER BY name");
                    while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = isset($_GET['category']) && $_GET['category'] == $category['ID'] ? 'selected' : '';
                        echo "<option value='{$category['ID']}' $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
                <select name="rating">
                    <option value="">All Ratings</option>
                    <?php
                    $ratings = [
                        '4-5' => '4-5 Stars',
                        '3-4' => '3-4 Stars',
                        '2-3' => '2-3 Stars',
                        '1-2' => '1-2 Stars'
                    ];
                    foreach ($ratings as $key => $label) {
                        $selected = isset($_GET['rating']) && $_GET['rating'] === $key ? 'selected' : '';
                        echo "<option value='$key' $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="filter-row">
                <input type="number" name="price_start" placeholder="Min Price" min="0" step="0.01" value="<?= isset($_GET['price_start']) ? htmlspecialchars($_GET['price_start']) : '' ?>">
                <span>-</span>
                <input type="number" name="price_end" placeholder="Max Price" min="0" step="0.01" value="<?= isset($_GET['price_end']) ? htmlspecialchars($_GET['price_end']) : '' ?>">
                <input type="text" name="search" placeholder="Search products..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" style="padding: 8px 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Apply Filters</button>
            </div>
        </form>
        <div class="found"><?= $total_items ?> style<?= $total_items !== 1 ? 's' : '' ?> found</div>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>">
                    <h3><?= htmlspecialchars($product['name'] ?? 'Unnamed Product') ?></h3>
                    <div class="price">
                        $<?= number_format($product['calculated_price'] ?? 0, 2) ?>
                    </div>
                    <div class="stars">
                        <?php
                        $rating = isset($product['rating']) ? (float)$product['rating'] : 0;
                        $fullStars = floor($rating);
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $fullStars ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <div class="status">
                        <?= isset($product['status']) && $product['status'] === 'in_stock' ? 'In Stock' : 'Out of Stock' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; text-align: center; color: #666;">No products found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <?php
            $query_params = $_GET;
            for ($i = 1; $i <= $total_pages; $i++):
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