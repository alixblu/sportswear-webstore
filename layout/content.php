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

        .product-rating i {
            color: #FFD700;
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

        <div class="home-header">
            <svg width="20" height="40" viewBox="0 0 20 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="20" height="40" rx="4" fill="#DB4444"/>
            </svg>
            <h1>Product</h1>
        </div>

        <!-- Product List -->
        <div class="product-list">
            <div class="product-card">
                <div class="product-image">
                    <img src="/img/products/1.jpg" alt="Tên sản phẩm 1" onerror="this.src='/img/products/default.jpg'">
                </div>
                <div class="product-name">Tên sản phẩm 1</div>
                <div class="product-price">
                    <span class="current-price">$49.99</span>
                </div>
                <div class="product-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <i class="far fa-star"></i>
                    <span>(3.5)</span>
                </div>
                <button class="buy-button">
                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                </button>
            </div>
        </div>

        <div class="pagination" id="pagination"></div>
    </div>
    <script src="../../JS/admin/product/product.js"></script>
    <script>

    //Product
    let currentPage = 1;
    const productsPerPage = 18;
    let allProducts = [];

    const renderPagination = (totalPages) => {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.textContent = i;
            pageLink.href = '#';

            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                renderProducts();
                renderPagination(totalPages); 
            });

            if (i === currentPage) {
                pageLink.classList.add('active');
            }

            pagination.appendChild(pageLink);
        }
    };


    const renderProducts = () => {
        const productList = document.querySelector('.product-list');
        productList.innerHTML = '';

        const start = (currentPage - 1) * productsPerPage;
        const end = start + productsPerPage;
        const pageProducts = allProducts.slice(start, end);

        if (pageProducts.length === 0) {
            productList.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-box-open"></i>
                    <h3>Không có sản phẩm nào.</h3>
                </div>`;
            return;
        }

        pageProducts.forEach(product => {
            const productCard = document.createElement('div');
            productCard.classList.add('product-card');

            const productImage = document.createElement('div');
            productImage.classList.add('product-image');
            const img = document.createElement('img');
            img.src = `/img/products/${product.ID}.jpg`;
            img.alt = product.name;
            img.onerror = function () { this.src = '/img/products/default.jpg'; };
            productImage.appendChild(img);

            const productName = document.createElement('div');
            productName.classList.add('product-name');
            productName.textContent = product.name || 'Tên sản phẩm';

            const productPrice = document.createElement('div');
            productPrice.classList.add('product-price');
            const currentPrice = document.createElement('span');
            currentPrice.classList.add('current-price');
            currentPrice.textContent = `$${product.price || '0.00'}`;
            productPrice.appendChild(currentPrice);

            const productRating = document.createElement('div');
            productRating.classList.add('product-rating');
            const stars = Math.round(product.rating || 5);
            for (let i = 0; i < 5; i++) {
                const starIcon = document.createElement('i');
                starIcon.classList.add(i < stars ? 'fas' : 'far', 'fa-star');
                productRating.appendChild(starIcon);
            }
            const ratingText = document.createElement('span');
            ratingText.textContent = `(${product.rating || 5})`;
            productRating.appendChild(ratingText);

            const buyButton = document.createElement('button');
            buyButton.classList.add('buy-button');
            buyButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ';

            productCard.appendChild(productImage);
            productCard.appendChild(productName);
            productCard.appendChild(productPrice);
            productCard.appendChild(productRating);
            productCard.appendChild(buyButton);

            productList.appendChild(productCard);
        });
    };

    const displayProducts = async () => {
        try {
            allProducts = await getAllProducts();
            const totalPages = Math.ceil(allProducts.length / productsPerPage);
            renderPagination(totalPages);
            renderProducts();
        } catch (error) {
            console.error('Error displaying products:', error);
        }
    };

        document.addEventListener('DOMContentLoaded', displayProducts);

        // <!-- Pagination -->

        // slide
        let slideIndex = 0;
        const slides = document.querySelector('.slides');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = document.querySelectorAll('.slides img').length;

        function showSlides() {
            slideIndex++;
            if (slideIndex >= totalSlides) slideIndex = 0;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        function currentSlide(index) {
            slideIndex = index;
            slides.style.transform = `translateX(${-slideIndex * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[slideIndex].classList.add('active');
        }

        setInterval(showSlides, 3000);

        dots[0].classList.add('active');
        // ----
    </script>
</body>
</html>