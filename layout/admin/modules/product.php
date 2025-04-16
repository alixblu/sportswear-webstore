<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <script src="../../JS/admin/product.js"></script>
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
                    <option value="4-5">4-5 Stars</option>
                    <option value="3-4">3-4 Stars</option>
                    <option value="2-3">2-3 Stars</option>
                    <option value="1-2">1-2 Stars</option>
                </select>
            </div>
            <div class="filter-group price-range-group">
                <div class="price-range-inputs">
                    <input type="number" id="priceStart" class="filter-input" placeholder="Min Price" min="0">
                    <span class="price-range-separator">-</span>
                    <input type="number" id="priceEnd" class="filter-input" placeholder="Max Price" min="0">
                </div>
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
                    <div class="discontinued-action">
                        <button class="btn btn-discontinued">
                            <i class="fas fa-ban"></i> Mark as Discontinued
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
                            <div class="info-item feature">
                                <div class="info-label">
                                    <i class="fas fa-barcode"></i> Product ID
                                </div>
                                <div class="info-value" id="modal-product-id">-</div>
                            </div>
                            <div class="info-item feature">
                                <div class="info-label">
                                    <i class="fas fa-tag"></i> Name
                                </div>
                                <div class="info-value" id="modal-product-name">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-layer-group"></i> Category
                                </div>
                                <div class="info-value" id="modal-product-category">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-copyright"></i> Brand
                                </div>
                                <div class="info-value" id="modal-product-brand">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-percentage"></i> Markup Percentage
                                </div>
                                <div class="info-value" id="modal-product-markup">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tags"></i> Discount ID
                                </div>
                                <div class="info-value" id="modal-product-discount-id">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-dollar-sign"></i> Base Price
                                </div>
                                <div class="info-value" id="modal-product-base-price">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-star"></i> Rating
                                </div>
                                <div class="info-value" id="modal-product-rating">-</div>
                        </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-box"></i> Stock
                    </div>
                                <div class="info-value" id="modal-product-stock">-</div>
                </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-info-circle"></i> Status
            </div>
                                <div class="info-value" id="modal-product-status">-</div>
                    </div>
                        </div>
                        <div class="info-item description">
                            <div class="info-label">
                                <i class="fas fa-align-left"></i> Description
                    </div>
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

                if (!Array.isArray(response)) {
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
                // Get product details
                let response = await getProductById(id);

                console.log('Product API Response:', response);

                if (!response || !response.data) {
                    throw new Error('No product data received');
                }

                const product = response.data;
                const modalElements = {
                    id: document.getElementById('modal-product-id'),
                    name: document.getElementById('modal-product-name'),
                    markup: document.getElementById('modal-product-markup'),
                    rating: document.getElementById('modal-product-rating'),
                    stock: document.getElementById('modal-product-stock'),
                    status: document.getElementById('modal-product-status'),
                    description: document.getElementById('modal-product-description'),
                    category: document.getElementById('modal-product-category'),
                    brand: document.getElementById('modal-product-brand'),
                    discountId: document.getElementById('modal-product-discount-id'),
                    basePrice: document.getElementById('modal-product-base-price')
                };

                // Update modal with product details
                modalElements.id.textContent = product.ID || '-';
                modalElements.name.textContent = product.name || '-';
                modalElements.markup.textContent = (product.markup_percentage || '0') + '%';
                modalElements.rating.innerHTML = renderStars(product.rating);
                modalElements.stock.textContent = product.stock || '0';
                modalElements.status.textContent = product.status === 'in_stock' ? 'In Stock' : 'Out of Stock';
                modalElements.description.textContent = product.description || 'No description available';
                modalElements.discountId.textContent = product.discountID || '-';
                modalElements.basePrice.textContent = product.price || '-';

                // Get and display category name
                if (product.categoryID) {
                    response = await getCategoryById(product.categoryID);
                    const category = response.data;
                    modalElements.category.textContent = category ? category.name : 'Unknown Category';
                } else {
                    modalElements.category.textContent = 'No category';
                }

                // Get and display brand name
                if (product.brandID) {
                    response = await getBrandById(product.brandID);
                    const brand = response.data;
                    modalElements.brand.textContent = brand ? brand.name : 'Unknown Brand';
                } else {
                    modalElements.brand.textContent = 'No brand';
                }

                // Get and display variants
                const res = await getProductVariants(id);
                const variants = res.data || [];
                const variantsList = document.getElementById('modal-variants-list');

                if (variantsList) {
                    variantsList.innerHTML = '';
                    if (variants && variants.length > 0) {
                        variants.forEach(variant => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${variant.Code || '-'}</td>
                                <td>${variant.fullName || '-'}</td>
                                <td>${variant.color || '-'}</td>
                                <td>${variant.size || '-'}</td>
                                <td>${variant.quantity || '0'}</td>
                                <td>${variant.price || '0'}</td>
                                <td>${variant.status || '-'}</td>
                            `;
                            variantsList.appendChild(row);
                        });
                    } else {
                        variantsList.innerHTML = '<tr><td colspan="8" class="text-center">No variants found</td></tr>';
                    }
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
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });


            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content and mark tab as active
            
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
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