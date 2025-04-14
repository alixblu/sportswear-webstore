<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/style.css">
    <link rel="stylesheet" href="../../css/admin/product.css">
    <style>
        .product-id-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: #fff;
            color: #333;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #000;
            box-shadow: 0 0 2px rgba(0,0,0,0.3);
        }
        .product-image {
            position: relative;
        }
    </style>
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
                <button id="addBtn" class="btn btn-primary" onclick="">
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
        <div class="product-grid" id="productGrid">
            <!-- Products will be loaded here dynamically -->
        </div>
    </div>

    <script src="../../JS/admin/product.js"></script>
    <script>
        // Function to render stars based on rating
        function renderStars(rating) {
            if (!rating) return '<div class="stars"><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></div>';
            
            const fullStars = Math.floor(rating);
            const halfStar = rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
            
            let starsHTML = '<div class="stars">';
            
            // Add full stars
            for (let i = 0; i < fullStars; i++) {
                starsHTML += '<i class="fas fa-star"></i>';
            }
            
            // Add half star if needed
            if (halfStar) {
                starsHTML += '<i class="fas fa-star-half-alt"></i>';
            }
            
            // Add empty stars
            for (let i = 0; i < emptyStars; i++) {
                starsHTML += '<i class="far fa-star"></i>';
            }
            
            starsHTML += '</div>';
            return starsHTML;
        }

        // Function to load and display products
        async function loadProducts() {
            try {
                const response = await getAllProducts();
                console.log('API Response:', response); // Debug log
                
                const productGrid = document.getElementById('productGrid');
                productGrid.innerHTML = '';

                // Check if response is an array
                if (!Array.isArray(response)) {
                    console.error('Expected an array of products, got:', response);
                    throw new Error('Invalid response format from server');
                }

                if (response.length === 0) {
                    productGrid.innerHTML = '<div class="no-products">No products found</div>';
                    return;
                }

                response.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card';
                    
                    productCard.innerHTML = `
                        <div class="product-image">
                            <span class="product-id-badge">#${product.ID}</span>
                            <i class="fas fa-tshirt"></i>
                            <span class="product-badge badge-${product.status === 'in_stock' ? 'in-stock' : 'out-stock'}">
                                ${product.status === 'in_stock' ? 'In Stock' : 'Out of Stock'}
                            </span>
                        </div>
                        <div class="product-info">
                            <h5 class="product-title">${product.name}</h5>
                            <div class="product-meta">
                                <span class="product-stock">Stock: ${product.stock || 0}</span>
                                <span class="product-markup">Markup: ${product.markup_percentage}%</span>
                            </div>
                            <div class="product-rating">
                                ${renderStars(product.rating)}
                                <span class="rating-count">${product.rating ? `(${product.rating})` : '(No rating)'}</span>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-outline" onclick="editProduct(${product.ID})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-primary" onclick="viewProduct(${product.ID})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    `;
                    
                    productGrid.appendChild(productCard);
                });
            } catch (error) {
                console.error('Error loading products:', error);
                const productGrid = document.getElementById('productGrid');
                productGrid.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading products. Please try again.</p>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        }

        // Load products when the page loads
        document.addEventListener('DOMContentLoaded', loadProducts);

        // Placeholder functions for edit and view
        function editProduct(id) {
            console.log('Edit product:', id);
            // Implement edit functionality
        }

        function viewProduct(id) {
            console.log('View product:', id);
            // Implement view functionality
        }
    </script>
</body>
</html>