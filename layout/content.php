<?php
// No database connection needed since we're using the API
// Pagination settings (handled client-side or via API)
$items_per_page = 10; // 2 rows x 5 products
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - SportsWear</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Keep the existing CSS unchanged */
        :root {
            --primary-color: #e63946;
            --secondary-color: #1d3557;
            --light-color: #f1faee;
            --dark-color: #457b9d;
            --gray-color: #a8dadc;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        .section-title {
            font-size: 28px;
            color: var(--secondary-color);
            margin: 0;
            position: relative;
            padding-left: 15px;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            bottom: 5px;
            width: 5px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }
        
        .view-all-button {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .view-all-button:hover {
            background-color: #c1121f;
        }
        
        .product-grid-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .product-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
        
        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            text-decoration: none;
            color: inherit;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-brand {
            font-size: 12px;
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .product-name {
            font-size: 16px;
            font-weight: 600;
            margin: 5px 0;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .stars {
            color: #ffc107;
            margin-right: 5px;
        }
        
        .rating-count {
            font-size: 12px;
            color: #666;
        }
        
        .product-status {
            font-size: 13px;
            padding: 5px 0;
            border-top: 1px solid #eee;
            color: #666;
            display: flex;
            align-items: center;
        }
        
        .status-icon {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .in-stock {
            background-color: #28a745;
        }
        
        .out-of-stock {
            background-color: #dc3545;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }
        
        .page-link, .page-current {
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        
        .page-link {
            border: 1px solid #ddd;
            color: var(--secondary-color);
            transition: all 0.3s;
        }
        
        .page-link:hover {
            background-color: #f0f0f0;
        }
        
        .page-current {
            background-color: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
        }
        
        .no-results {
            text-align: center;
            grid-column: 1 / -1;
            padding: 50px 0;
            color: #666;
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
        
        @media (max-width: 1200px) {
            .product-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .product-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .product-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Explore Our Products</h1>
            <a href="?view_all=1" class="view-all-button">
                <i class="fas fa-eye"></i> View All
            </a>
        </div>
        
        <!-- Product Grid - 2 rows of 5 products each -->
        <div class="product-grid-container" id="product-grid">
            <!-- Products will be dynamically inserted here -->
        </div>
        
        <!-- Pagination -->
        <div class="pagination" id="pagination">
            <!-- Pagination links will be dynamically inserted here -->
        </div>
    </div>

    <script src="../../JS/admin/product.js"></script>
    <script>
        // Function to render stars based on rating
        function renderStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating - fullStars >= 0.5;
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= fullStars) {
                    starsHtml += '<i class="fas fa-star"></i>';
                } else if (hasHalfStar && i === fullStars + 1) {
                    starsHtml += '<i class="fas fa-star-half-alt"></i>';
                } else {
                    starsHtml += '<i class="far fa-star"></i>';
                }
            }
            return starsHtml;
        }

        // Function to render products
        async function renderProducts(page = <?php echo $page; ?>, itemsPerPage = <?php echo $items_per_page; ?>) {
            try {
                const products = await getAllProducts();
                const productGrid = document.getElementById('product-grid');
                const paginationContainer = document.getElementById('pagination');
                productGrid.innerHTML = '';
                paginationContainer.innerHTML = '';

                // Filter products by status (optional, assuming API returns all statuses)
                const filteredProducts = products.filter(product => product.status === 'in_stock');

                // Pagination calculations
                const totalItems = filteredProducts.length;
                const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

                // Split products into chunks of 5 for each row
                const productChunks = [];
                for (let i = 0; i < paginatedProducts.length; i += 5) {
                    productChunks.push(paginatedProducts.slice(i, i + 5));
                }
                const displayChunks = productChunks.slice(0, 2); // Limit to 2 rows

                if (displayChunks.length > 0) {
                    displayChunks.forEach(chunk => {
                        const row = document.createElement('div');
                        row.className = 'product-row';
                        chunk.forEach(product => {
                            const defaultImage = '/sportswear-webstore/img/products/default.jpg';
                            const rating = parseFloat(product.rating || 0);
                            const price = parseFloat(product.price || 0);
                            const brandName = product.brand_name || 'Unknown Brand';
                            const productCard = `
                                <a href="product_detail.php?id=${product.id}" class="product-card">
                                    ${product.status === 'out_of_stock' ? '<div class="product-badge">Out of Stock</div>' : ''}
                                    <img src="${product.image}" alt="${product.name}" class="product-image" 
                                         onerror="this.src='${defaultImage}'" loading="lazy">
                                    <div class="product-info">
                                        <div class="product-brand">${brandName}</div>
                                        <h3 class="product-name">${product.name}</h3>
                                        <div class="product-price">$${price.toFixed(2)}</div>
                                        <div class="product-rating">
                                            <div class="stars">${renderStars(rating)}</div>
                                            <span class="rating-count">(${rating.toFixed(1)})</span>
                                        </div>
                                        <div class="product-status">
                                            <span class="status-icon ${product.status === 'in_stock' ? 'in-stock' : 'out-of-stock'}"></span>
                                            ${product.status === 'in_stock' ? 'In Stock' : 'Out of Stock'}
                                        </div>
                                    </div>
                                </a>
                            `;
                            row.innerHTML += productCard;
                        });
                        productGrid.appendChild(row);
                    });
                } else {
                    productGrid.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-search"></i>
                            <h3>No products found</h3>
                            <p>Please check back later</p>
                        </div>
                    `;
                }

                // Render pagination
                if (totalPages > 1) {
                    const queryParams = new URLSearchParams(window.location.search);
                    if (page > 1) {
                        queryParams.set('page', page - 1);
                        paginationContainer.innerHTML += `<a href="?${queryParams.toString()}" class="page-link">&laquo; Previous</a>`;
                    }

                    const startPage = Math.max(1, page - 2);
                    const endPage = Math.min(totalPages, page + 2);

                    if (startPage > 1) {
                        queryParams.set('page', 1);
                        paginationContainer.innerHTML += `<a href="?${queryParams.toString()}" class="page-link">1</a>`;
                        if (startPage > 2) paginationContainer.innerHTML += '<span class="page-link">...</span>';
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        queryParams.set('page', i);
                        if (i === page) {
                            paginationContainer.innerHTML += `<span class="page-current">${i}</span>`;
                        } else {
                            paginationContainer.innerHTML += `<a href="?${queryParams.toString()}" class="page-link">${i}</a>`;
                        }
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) paginationContainer.innerHTML += '<span class="page-link">...</span>';
                        queryParams.set('page', totalPages);
                        paginationContainer.innerHTML += `<a href="?${queryParams.toString()}" class="page-link">${totalPages}</a>`;
                    }

                    if (page < totalPages) {
                        queryParams.set('page', page + 1);
                        paginationContainer.innerHTML += `<a href="?${queryParams.toString()}" class="page-link">Next &raquo;</a>`;
                    }
                }
            } catch (error) {
                console.error('Error rendering products:', error);
                document.getElementById('product-grid').innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Error loading products</h3>
                        <p>Please try again later</p>
                    </div>
                `;
            }
        }

        // Load products when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            renderProducts();
        });
    </script>
</body>
</html>