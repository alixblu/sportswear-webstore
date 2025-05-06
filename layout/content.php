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
$items_per_page = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Count total products
$total_query = "
    SELECT COUNT(DISTINCT p.ID)
    FROM product p
    LEFT JOIN productvariant pv ON p.ID = pv.productID
    WHERE p.status = 'in_stock'
";
$stmt = $conn->prepare($total_query);
$stmt->execute();
$total_items = $stmt->fetchColumn();
$total_pages = max(1, ceil($total_items / $items_per_page));

// Fetch products
$query = "
    SELECT 
        p.ID, p.name, p.status, p.markup_percentage,
        MIN(pv.price + (pv.price * p.markup_percentage / 100)) AS calculated_price,
        COALESCE(r.avg_rating, 0) AS rating,
        b.name AS brand_name,
        c.name AS category_name
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
    WHERE p.status = 'in_stock'
    GROUP BY p.ID, p.name, p.status, p.markup_percentage, b.name, c.name
    ORDER BY p.ID DESC
    LIMIT :offset, :items_per_page
";
$stmt = $conn->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm đang bán - SportsWear</title>
    <link rel="stylesheet" href="/sportswear-webstore/css/content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pagination {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .page-link {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }
        .page-link:hover {
            background-color: #f0f0f0;
        }
        .page-link.active {
            background-color: #e63946;
            color: white;
            border-color: #e63946;
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
        .slider-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }
        .slider-container {
            width: 100%;
            max-width: 900px;
            height: 300px;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }
        .slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .slides img {
            width: 100%;
            height: 100%;
            flex: 0 0 100%;
            object-fit: cover;
            object-position: center;
        }
        .dots {
            margin-top: 10px;
        }
        .dot {
            height: 10px;
            width: 10px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
            cursor: pointer;
        }
        .dot.active {
            background-color: #ff0000;
        }

        /* text-content */
        .text-content {
            position: absolute;
            bottom: 20%;
            left: 5%;
            text-align: left; 

        }

        .text-content h1 {
            font-size: 45px;
            margin: 0;
            line-height: 1.4;
        }

        .shop-now {
            display: inline-block;
            margin-top: 15px;
            margin-left: 15px;
            color: white;
            font-size: 14px;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .shop-now:hover {
            border-color: white;
        }
    </style>
</head>
<body>
    <div class="product-section">
        <div class="slider-wrapper">
            <div class="slider-container">
                <div class="slides">
                    <img src="img/slider/1.svg" alt="Hình 1">
                    <img src="img/slider/2.svg" alt="Hình 2">
                    <img src="img/slider/3.svg" alt="Hình 3">
                </div>
            </div>
            <div class="dots">
                <span class="dot" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
            </div>

            <div class="text-content">
                <h1>Up to 10%<br>off Voucher</h1>
                <a href="#" class="shop-now">Shop Now →</a>
            </div>
        </div>

        <div class="section-header">
            <h1>Sản phẩm đang bán</h1>
            <span class="search-count"><?= $total_items ?> sản phẩm được tìm thấy</span>
        </div>

        <!-- Product List -->
        <div class="product-list">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): 
                    $image_path = "/img/products/" . $product['ID'] . ".jpg";
                    $default_image = "/img/products/default.jpg";
                    $image_src = file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path) ? $image_path : $default_image;
                    $rating = (float)$product['rating'];
                    $full_stars = floor($rating);
                    $has_half_star = $rating - $full_stars >= 0.5;
                ?>
                    <a href="/sportswear-webstore/layout/client/product_detail.php?id=<?= $product['ID'] ?>" class="product-card">
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
                        <button class="buy-button">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                        </button>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Hiện tại không có sản phẩm nào đang bán</p>
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
                    $prev_url = '/sportswear-webstore/layout/content.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($prev_url) . '" class="page-link">« Trước</a>';
                }

                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    $query_params['page'] = 1;
                    $first_url = '/sportswear-webstore/layout/content.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($first_url) . '" class="page-link">1</a>';
                    if ($start_page > 2) echo '<span class="page-link">...</span>';
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $query_params['page'] = $i;
                    $page_url = '/sportswear-webstore/layout/content.php?' . http_build_query($query_params);
                    if ($i === $page) {
                        echo '<span class="page-link active">' . $i . '</span>';
                    } else {
                        echo '<a href="' . htmlspecialchars($page_url) . '" class="page-link">' . $i . '</a>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) echo '<span class="page-link">...</span>';
                    $query_params['page'] = $total_pages;
                    $last_url = '/sportswear-webstore/layout/content.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($last_url) . '" class="page-link">' . $total_pages . '</a>';
                }

                if ($page < $total_pages) {
                    $query_params['page'] = $page + 1;
                    $next_url = '/sportswear-webstore/layout/client/content.php?' . http_build_query($query_params);
                    echo '<a href="' . htmlspecialchars($next_url) . '" class="page-link">Tiếp theo »</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        let slideIndex = 0;
        const slides = document.querySelector('.slides');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = document.querySelectorAll('.slides img').length;

        function showSlides() {
            slideIndex++;
            if (slideIndex >= totalSlides) slideIndex = 0;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            
            // Cập nhật trạng thái dot
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        function currentSlide(index) {
            slideIndex = index;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        // Tự động chuyển slide mỗi 3 giây
        setInterval(showSlides, 3000);

        // Đặt dot đầu tiên là active khi tải trang
        dots[0].classList.add('active');
    </script>
</body>
</html>