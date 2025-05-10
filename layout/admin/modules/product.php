<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <script src="../../JS/admin/product/product.js" type="module"></script>
    <a href="../components/product/detail_modal.php"></a>
</head>

<body>
    <div class="main-content">
        <div id="pageTitle" class="page-title">
            <div class="title">Products</div>
            <div class="action-buttons">
                <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;">
                <button id="exportBtn" class="btn btn-outline">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <!-- Product Filters -->
        <div class="product-filters">
            <div class="filter-group search-group">
                <input type="text" id="search" class="filter-input" placeholder="Search products...">
            </div>
            <div class="filter-group">
                <select id="category" class="filter-select">
                    <option value="">All Categories</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="brand" class="filter-select">
                    <option value="">All Brands</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="status" class="filter-select">
                    <option value="">All Status</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="rating" class="filter-select">
                    <option value="">All Ratings</option>
                    <option value="5">4-5 Stars</option>
                    <option value="4">3-4 Stars</option>
                    <option value="3">2-3 Stars</option>
                    <option value="2">1-2 Stars</option>
                    <option value="1">0 Star</option>
                </select>
            </div>
            <!--
            <div class="filter-group price-range-group">
                <div class="price-range-inputs">
                    <input type="number" id="priceStart" class="filter-input" placeholder="Min Price" min="0">
                    <span class="price-range-separator">-</span>
                    <input type="number" id="priceEnd" class="filter-input" placeholder="Max Price" min="0">
                </div>
            </div>
-->
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            <!-- Products will be loaded here dynamically -->
        </div>

        <!-- Pagination  -->
        <div id="pagination">
            <!-- Insert page items -->
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal-overlay" id="productModal">
        <?php
        include(__DIR__ . '/../components/product/detail_modal.php');
        ?>
    </div>
</body>

</html>