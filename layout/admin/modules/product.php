<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        /* Product Card Styles */
        .product-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }

        .product-info {
            padding: 12px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
            line-height: 1.3;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
            color: var(--text-light);
        }

        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .stars {
            color: var(--warning);
            margin-right: 6px;
            font-size: 12px;
        }

        .rating-count {
            font-size: 11px;
            color: var(--text-light);
        }

        .product-actions {
            display: flex;
            justify-content: center;
            margin-top: 12px;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: var(--radius);
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow: hidden;
            z-index: 1001;
            box-shadow: var(--shadow);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--border);
            background-color: rgba(248, 249, 250, 0.5);
        }

        .modal-header h2 {
            font-size: 19px;
            font-weight: 600;
            display: flex;
            align-items: center;
            color: var(--dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-light);
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--danger);
            transform: rotate(90deg);
        }

        .modal-body {
            display: flex;
            flex-direction: row;
            height: calc(90vh - 120px);
        }

        .product-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: var(--light);
            border-right: 1px solid var(--border);
            width: 240px;
        }

        .product-image-large {
            width: 160px;
            height: 160px;
            background: white;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .product-image-large i {
            font-size: 80px;
            color: var(--border);
        }

        .product-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .product-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .product-info-section {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 24px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            color: var(--text-light);
            font-weight: 500;
        }

        .tab.active {
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .info-item {
            background: var(--light);
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .info-label {
            font-size: 11px;
            color: var(--text-light);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .info-value {
            font-size: 13px;
            color: var(--text);
            font-weight: 500;
        }

        .info-item[style*="margin-top"] {
            margin-top: 12px;
        }

        .variants-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        .variants-table th {
            background: var(--light);
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--text);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .variants-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .variants-table tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
        }

        #variants-tab {
            overflow-x: auto;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }

        .status-badge i {
            margin-right: 6px;
            font-size: 12px;
        }

        .status-in_stock {
            background-color: rgba(76, 201, 240, 0.15);
            color: #0891b2;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .status-out_of_stock {
            background-color: rgba(247, 37, 133, 0.15);
            color: #db2777;
            border: 1px solid rgba(247, 37, 133, 0.3);
        }

        .btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn i {
            font-size: 12px;
            margin-right: 4px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(58, 12, 163, 0.2);
        }

        .btn-outline {
            border: 2px solid var(--primary-light);
            color: var(--primary);
            background-color: transparent;
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(58, 12, 163, 0.2);
        }

        /* Export button specific styles */
        #exportBtn {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
        }

        #exportBtn i {
            font-size: 14px;
            margin-right: 6px;
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

    <!-- Modal Structure -->
    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Product Details</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="product-image-section">
                    <div class="product-image-large">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Product
                        </button>
                        <button class="btn btn-outline">
                            <i class="fas fa-image"></i> Change Image
                        </button>
                    </div>
                </div>
                <div class="product-info-section">
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('details')">Details</div>
                        <div class="tab" onclick="switchTab('variants')">Variants</div>
            </div>

                    <div id="details-tab" class="tab-content active">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Product ID</div>
                                <div class="info-value" id="modal-product-id">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Name</div>
                                <div class="info-value" id="modal-product-name">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Category</div>
                                <div class="info-value" id="modal-product-category">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Brand</div>
                                <div class="info-value" id="modal-product-brand">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Markup Percentage</div>
                                <div class="info-value" id="modal-product-markup">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Rating</div>
                                <div class="info-value" id="modal-product-rating">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Stock</div>
                                <div class="info-value" id="modal-product-stock">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value" id="modal-product-status">-</div>
                            </div>
                        </div>
                        <div class="info-item" style="margin-top: 15px;">
                            <div class="info-label">Description</div>
                            <div class="info-value" id="modal-product-description">-</div>
                        </div>
                    </div>

                    <div id="variants-tab" class="tab-content">
                        <table class="variants-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Full Name</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Weight</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="modal-variants-list">
                                <!-- Variants will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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

        // Modal functions
        async function viewProduct(id) {
            const modal = document.getElementById('productModal');
            modal.style.display = 'block';
            
            try {
                console.log('Fetching product with ID:', id);
                // Get product details
                let response = await getProductById(id);
                console.log('Product API Response:', response);
                
                if (!response || !response.data) {
                    throw new Error('No product data received');
                }

                const product = response.data;
                console.log('Product Data:', product);

                // Debug: Check if modal elements exist
                const modalElements = {
                    id: document.getElementById('modal-product-id'),
                    name: document.getElementById('modal-product-name'),
                    markup: document.getElementById('modal-product-markup'),
                    rating: document.getElementById('modal-product-rating'),
                    stock: document.getElementById('modal-product-stock'),
                    status: document.getElementById('modal-product-status'),
                    description: document.getElementById('modal-product-description'),
                    category: document.getElementById('modal-product-category'),
                    brand: document.getElementById('modal-product-brand')
                };

                console.log('Modal Elements:', modalElements);

                // Update modal with product details
                if (modalElements.id) {
                    modalElements.id.textContent = product.ID || '-';
                    console.log('Setting product ID to:', product.ID);
                } else {
                    console.error('Product ID element not found');
                }

                if (modalElements.name) {
                    modalElements.name.textContent = product.name || '-';
                    console.log('Setting product name to:', product.name);
                }

                if (modalElements.markup) {
                    modalElements.markup.textContent = (product.markup_percentage || '0') + '%';
                    console.log('Setting markup to:', product.markup_percentage);
                }

                if (modalElements.rating) {
                    modalElements.rating.innerHTML = renderStars(product.rating);
                    console.log('Setting rating to:', product.rating);
                }

                if (modalElements.stock) {
                    modalElements.stock.textContent = product.stock || '0';
                    console.log('Setting stock to:', product.stock);
                }

                if (modalElements.status) {
                    modalElements.status.textContent = product.status === 'in_stock' ? 'In Stock' : 'Out of Stock';
                    console.log('Setting status to:', product.status);
                }

                if (modalElements.description) {
                    modalElements.description.textContent = product.description || 'No description available';
                    console.log('Setting description to:', product.description);
                }

                // Get and display category name
                if (product.categoryID) {
                    console.log('Fetching category with ID:', product.categoryID);
                    response = await getCategoryById(product.categoryID);
                    category = response.data;
                    console.log('Category API Response:', category);
                    if (modalElements.category) {
                        modalElements.category.textContent = category ? category.name : 'Unknown Category';
                        console.log('Setting category to:', category ? category.name : 'Unknown Category');
                    }
                } else {
                    if (modalElements.category) {
                        modalElements.category.textContent = 'No category';
                    }
                }

                // Get and display brand name
                if (product.brandID) {
                    console.log('Fetching brand with ID:', product.brandID);
                    response = await getBrandById(product.brandID);
                    brand = response.data;
                    console.log('Brand API Response:', brand);
                    if (modalElements.brand) {
                        modalElements.brand.textContent = brand ? brand.name : 'Unknown Brand';
                        console.log('Setting brand to:', brand ? brand.name : 'Unknown Brand');
                    }
                } else {
                    if (modalElements.brand) {
                        modalElements.brand.textContent = 'No brand';
                    }
                }

                // Get and display variants
                console.log('Fetching variants for product ID:', id);
                const variants = await getProductVariants(id);
                console.log('Variants API Response:', variants);
                const variantsList = document.getElementById('modal-variants-list');
                
                if (variantsList) {
                    variantsList.innerHTML = '';
                    console.log('Variants list element found');

                    if (variants && variants.length > 0) {
                        variants.forEach(variant => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${variant.Code || '-'}</td>
                                <td>${variant.fullName || '-'}</td>
                                <td>${variant.color || '-'}</td>
                                <td>${variant.size || '-'}</td>
                                <td>${variant.weight || '-'}</td>
                                <td>${variant.quantity || '0'}</td>
                                <td>${variant.price || '0'}</td>
                                <td>${variant.status || '-'}</td>
                            `;
                            variantsList.appendChild(row);
                        });
                        console.log('Added variants to table');
                    } else {
                        variantsList.innerHTML = '<tr><td colspan="8" class="text-center">No variants found</td></tr>';
                        console.log('No variants found');
                    }
                } else {
                    console.error('Variants list element not found');
                }
            } catch (error) {
                console.error('Error loading product details:', error);
                alert('Error loading product details: ' + error.message);
            }
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.style.display = 'none';
        }

        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content and mark tab as active
            document.getElementById(`${tabName}-tab`).classList.add('active');
            document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html> 