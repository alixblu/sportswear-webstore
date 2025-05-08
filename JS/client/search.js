const searchForm = document.getElementById('searchForm');
const filterForm = document.querySelector('.filter-section form');
const productList = document.querySelector('.product-list');
const pagination = document.querySelector('.pagination');
const sectionHeader = document.querySelector('.section-header');
const pageContainer = document.querySelector('.page-container');

async function performSearch(e) {
    e.preventDefault();
    const searchInput = document.getElementById('searchInput').value.trim();

    if (!searchInput) return;

    try {
        // Kiểm tra nếu từ khóa tìm kiếm khớp với thương hiệu
        const brandResponse = await fetch(`http://localhost/sportswear-webstore/src/router/productrouter.php?action=getBrandByName&name=${encodeURIComponent(searchInput)}`);
        const brandData = await brandResponse.json();
        if (brandData.status === 200 && brandData.data && brandData.data.ID) {
            window.location.href = `/sportswear-webstore/layout/client/search_results.php?brand=${brandData.data.ID}`;
            return;
        }

        // Kiểm tra nếu từ khóa tìm kiếm khớp với danh mục
        const categoryResponse = await fetch(`http://localhost/sportswear-webstore/src/router/productrouter.php?action=getCategoryByName&name=${encodeURIComponent(searchInput)}`);
        const categoryData = await categoryResponse.json();
        if (categoryData.status === 200 && categoryData.data && categoryData.data.ID) {
            window.location.href = `/sportswear-webstore/layout/client/search_results.php?category=${categoryData.data.ID}`;
            return;
        }

        // Nếu không khớp thương hiệu hoặc danh mục, thực hiện tìm kiếm thông thường
        window.location.href = `/sportswear-webstore/layout/client/search_results.php?search=${encodeURIComponent(searchInput)}`;
    } catch (error) {
        console.error('Lỗi khi kiểm tra thương hiệu/danh mục:', error);
        window.location.href = `/sportswear-webstore/layout/client/search_results.php?search=${encodeURIComponent(searchInput)}`;
    }
}

async function updateResults(params = {}) {
    const itemsPerPage = 12;
    params.page = params.page || 1;
    params.limit = itemsPerPage;

    // Đảm bảo rằng price_start và price_end được gửi dưới dạng min_price và max_price để đồng bộ với backend
    if (params.price_start) params.min_price = params.price_start;
    if (params.price_end) params.max_price = params.price_end;
    delete params.price_start;
    delete params.price_end;

    const queryString = new URLSearchParams(params).toString();
    const apiUrl = `http://localhost/sportswear-webstore/src/router/productrouter.php?action=getFilteredProducts&${queryString}`;

    try {
        const response = await fetch(apiUrl);
        const data = await response.json();

        if (data.status !== 200) {
            productList.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Lỗi khi tải dữ liệu</h3>
                    <p>${data.message || 'Vui lòng thử lại sau'}</p>
                </div>`;
            pagination.innerHTML = '';
            return;
        }

        const products = data.data || [];
        const totalItems = products.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
        const offset = (params.page - 1) * itemsPerPage;
        const paginatedProducts = products.slice(offset, offset + itemsPerPage);

        // Cập nhật tiêu đề
        let headerText = 'Tất cả sản phẩm';
        if (params.search) {
            headerText = `Kết quả tìm kiếm: "${params.search}"`;
        } else if (params.brand) {
            const brandName = await getNameFromId(params.brand, 'Brand');
            headerText = `Kết quả theo thương hiệu: ${brandName}`;
        } else if (params.category) {
            const categoryName = await getNameFromId(params.category, 'Category');
            headerText = `Kết quả theo danh mục: ${categoryName}`;
        }
        sectionHeader.innerHTML = `
            <h1>${headerText}</h1>
            <span class="search-count">${totalItems} sản phẩm được tìm thấy</span>
        `;

        // Cập nhật danh sách sản phẩm
        productList.innerHTML = paginatedProducts.length > 0
            ? paginatedProducts.map(product => {
                const imagePath = `/sportswear-webstore/img/products/${product.ID}.jpg`;
                const defaultImage = '/sportswear-webstore/img/products/default.jpg';
                const rating = parseFloat(product.rating || 0);
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating - fullStars >= 0.5;
                let ratingHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= fullStars) {
                        ratingHtml += '<i class="fas fa-star"></i>';
                    } else if (hasHalfStar && i === fullStars + 1) {
                        ratingHtml += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        ratingHtml += '<i class="far fa-star"></i>';
                    }
                }

                // Tính giá bán: price + price * markup_percentage
                const basePrice = parseFloat(product.price) || 0;
                const markupPercentage = parseFloat(product.markup_percentage) || 0;
                if (isNaN(basePrice) || basePrice <= 0) {
                    console.warn(`Giá cơ bản không hợp lệ cho sản phẩm ID ${product.ID}: ${product.price}`);
                }
                if (isNaN(markupPercentage)) {
                    console.warn(`Phần trăm markup không hợp lệ cho sản phẩm ID ${product.ID}: ${product.markup_percentage}`);
                }
                const sellingPrice = basePrice + (basePrice * markupPercentage / 100);
                // Định dạng giá bán theo kiểu Việt Nam: 1,234,567 ₫
                const formattedPrice = Math.round(sellingPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' ₫';

                return `
                    <a href="/sportswear-webstore/layout/client/product_detail.php?id=${product.ID}" class="product-card">
                        ${product.status === 'out_of_stock' ? '<div class="discount-badge">Hết hàng</div>' : ''}
                        <div class="product-image">
                            <img src="${imagePath}" alt="${product.name}" onerror="this.src='${defaultImage}'">
                        </div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">
                            <span class="current-price">${formattedPrice}</span>
                        </div>
                        <div class="product-rating">
                            ${ratingHtml}
                            <span>(${rating.toFixed(1)})</span>
                        </div>
                        <button class="buy-button" ${product.status === 'out_of_stock' ? 'disabled' : ''}>
                            <i class="fas fa-shopping-cart"></i>
                            ${product.status === 'in_stock' ? 'Thêm vào giỏ' : 'Hết hàng'}
                        </button>
                    </a>
                `;
            }).join('')
            : `
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                </div>`;

        // Cập nhật phân trang
        pagination.innerHTML = '';
        if (totalPages > 1) {
            let paginationHtml = '';

            if (params.page > 1) {
                paginationHtml += `<a href="#" class="page-link" data-page="${params.page - 1}">« Trước</a>`;
            }

            const startPage = Math.max(1, params.page - 2);
            const endPage = Math.min(totalPages, params.page + 2);

            if (startPage > 1) {
                paginationHtml += `<a href="#" class="page-link" data-page="1">1</a>`;
                if (startPage > 2) paginationHtml += '<span class="page-link">...</span>';
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += i === params.page
                    ? `<span class="page-link active">${i}</span>`
                    : `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) paginationHtml += '<span class="page-link">...</span>';
                paginationHtml += `<a href="#" class="page-link" data-page="${totalPages}">${totalPages}</a>`;
            }

            if (params.page < totalPages) {
                paginationHtml += `<a href="#" class="page-link" data-page="${params.page + 1}">Tiếp theo »</a>`;
            }

            pagination.innerHTML = paginationHtml;

            // Thêm sự kiện cho các liên kết phân trang
            pagination.querySelectorAll('.page-link[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    params.page = parseInt(link.dataset.page);
                    updateResults(params);
                });
            });
        }
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error);
        productList.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>Lỗi khi tải dữ liệu</h3>
                <p>Vui lòng thử lại sau</p>
            </div>`;
        pagination.innerHTML = '';
    }
}

async function getNameFromId(id, type) {
    try {
        const url = `http://localhost/sportswear-webstore/src/router/productrouter.php?action=get${type}ById&id=${id}`;
        const response = await fetch(url);
        const data = await response.json();
        return (data.status === 200 && data.data && data.data.name) ? data.data.name : 'Không xác định';
    } catch (error) {
        console.error(`Lỗi khi lấy tên ${type.toLowerCase()}:`, error);
        return 'Không xác định';
    }
}

if (searchForm) {
    searchForm.addEventListener('submit', performSearch);
}

if (filterForm) {
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(filterForm);
        const params = {};
        formData.forEach((value, key) => {
            if (value) params[key] = value;
        });
        params.page = 1; // Reset về trang 1 khi áp dụng bộ lọc
        updateResults(params);
    });
}

// Xử lý thay đổi bộ lọc mà không cần submit form
const filterInputs = filterForm?.querySelectorAll('.filter-select, .filter-input');
filterInputs?.forEach(input => {
    input.addEventListener('change', () => {
        const formData = new FormData(filterForm);
        const params = {};
        formData.forEach((value, key) => {
            if (value) params[key] = value;
        });
        params.page = 1;
        updateResults(params);
    });
});