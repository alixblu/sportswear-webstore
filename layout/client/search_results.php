<?php
session_start();
$initial_params = [];
foreach ($_GET as $key => $value) {
    if (!empty($value)) {
        if ($key === 'price_start') {
            $initial_params['min_price'] = htmlspecialchars($value);
        } elseif ($key === 'price_end') {
            $initial_params['max_price'] = htmlspecialchars($value);
        } else {
            $initial_params[$key] = htmlspecialchars($value);
        }
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
    <!-- Ensure Font Awesome 6 Free is loaded correctly -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            display: flex;
            justify-content: center;
            gap: 10px;
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
            smug: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-error {
            color: #721c24;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        /* Additional CSS for star ratings */
        .product-rating i {
            font-family: "Font Awesome 6 Free" !important;
            font-style: normal;
            font-weight: 900; /* Ensure solid icons for filled stars */
            color: #f39c12;
            font-size: 0.9rem;
        }
        .product-rating .fa-star-half-alt {
            font-weight: 900; /* Ensure half-star is rendered correctly */
        }
        .product-rating .far.fa-star {
            font-weight: 400; /* Ensure empty stars are regular weight */
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
                            </select>
                            <div class="filter-error" id="brand-error">Không thể tải danh sách thương hiệu</div>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Danh mục</label>
                            <select class="filter-select" name="category">
                                <option value="">Tất cả danh mục</option>
                            </select>
                            <div class="filter-error" id="category-error">Không thể tải danh sách danh mục</div>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Khoảng giá</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="number" class="filter-input" name="min_price" placeholder="Từ" value="<?= isset($initial_params['min_price']) ? $initial_params['min_price'] : '' ?>">
                                <input type="number" class="filter-input" name="max_price" placeholder="Đến" value="<?= isset($initial_params['max_price']) ? $initial_params['max_price'] : '' ?>">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Trạng thái</label>
                            <select class="filter-select" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="in_stock" <?= isset($initial_params['status']) && $initial_params['status'] === 'in_stock' ? 'selected' : '' ?>>Còn hàng</option>
                                <option value="out_of_stock" <?= isset($initial_params['status']) && $initial_params['status'] === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Sắp xếp theo</label>
                            <select class="filter-select" name="sort">
                                <option value="newest" <?= isset($initial_params['sort']) && $initial_params['sort'] === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="price_asc" <?= isset($initial_params['sort']) && $initial_params['sort'] === 'price_asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                                <option value="price_desc" <?= isset($initial_params['sort']) && $initial_params['sort'] === 'price_desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                                <option value="rating_desc" <?= isset($initial_params['sort']) && $initial_params['sort'] === 'rating_desc' ? 'selected' : '' ?>>Đánh giá cao nhất</option>
                            </select>
                        </div>
                        <?php if (!empty($initial_params['search'])): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($initial_params['search']) ?>">
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
        const initialParams = <?php echo $initial_params_json; ?>;
        window.addEventListener('DOMContentLoaded', () => {
            loadFilters();
            if (Object.keys(initialParams).length > 0) {
                updateResults(initialParams);
            }
        });
    </script>
    <script src="../../js/client/search.js" defer></script>
</body>
</html>