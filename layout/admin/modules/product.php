<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/admin/product.css">

    <script src="../../JS/admin/product/product.js"></script>
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
                    <option value="all">All Categories</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="brand" class="filter-select">
                    <option value="all">All Brands</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="status" class="filter-select">
                    <option value="all">All Status</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="rating" class="filter-select">
                    <option value="all">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
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
        <?php
        include(__DIR__ . '/../components/product/detail_modal.php');
        ?>
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

        var product = null;
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
                product = response.data;
                const imgElement = document.getElementById('modal-product-image');

                // Set the image source and handle errors using the 'error' event listener
                imgElement.onerror = function() {
                    this.src = '../../img/products/default.png';
                };

                // Assign the image source
                imgElement.src = `../../img/products/${product.ID}.jpg`;


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
    </script>
</body>

</html>