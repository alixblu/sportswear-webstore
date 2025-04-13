<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/style.css">
    <link rel="stylesheet" href="../../css/admin/product.css">
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
                <button id="addBtn" class="btn btn-primary" onclick="showFormAddUser()">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>
        </div>

        <!-- Product Filters -->
        <div class="product-filters">
            <div class="filter-group">
                <label for="category">Category:</label>
                <select id="category" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="1">T-Shirts</option>
                    <option value="2">Shoes</option>
                    <option value="3">Accessories</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="status">Status:</label>
                <select id="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search">Search:</label>
                <input type="text" id="search" class="filter-input" placeholder="Search products...">
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <!-- Sample Product 1 -->
            <div class="product-card">
                <div class="product-image">
                    <i class="fas fa-tshirt"></i>
                    <span class="product-badge badge-in-stock">In Stock</span>
                </div>
                <div class="product-info">
                    <h5 class="product-title">Classic White T-Shirt</h5>
                    <div class="product-meta">
                        <span class="product-stock">Stock: 50</span>
                        <span class="product-price">$29.99</span>
                    </div>
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-count">(4.5)</span>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Product 2 -->
            <div class="product-card">
                <div class="product-image">
                    <i class="fas fa-running"></i>
                    <span class="product-badge badge-in-stock">In Stock</span>
                </div>
                <div class="product-info">
                    <h5 class="product-title">Running Shoes</h5>
                    <div class="product-meta">
                        <span class="product-stock">Stock: 25</span>
                        <span class="product-price">$89.99</span>
                    </div>
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="rating-count">(5.0)</span>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Product 3 -->
            <div class="product-card">
                <div class="product-image">
                    <i class="fas fa-basketball-ball"></i>
                    <span class="product-badge badge-out-stock">Out of Stock</span>
                </div>
                <div class="product-info">
                    <h5 class="product-title">Basketball Jersey</h5>
                    <div class="product-meta">
                        <span class="product-stock">Stock: 0</span>
                        <span class="product-price">$49.99</span>
                    </div>
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="rating-count">(4.0)</span>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>