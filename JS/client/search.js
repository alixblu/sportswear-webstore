const searchForm = document.getElementById('searchForm');
const filterForm = document.querySelector('.filter-section form');
const productList = document.querySelector('.product-list');
const pagination = document.querySelector('.pagination');
const sectionHeader = document.querySelector('.section-header');
const pageContainer = document.querySelector('.page-container');

async function performSearch(e) {
    e.preventDefault();
    const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();

    if (!searchInput) {
        alert('Vui lòng nhập từ khóa tìm kiếm!');
        return;
    }

    try {
        // Kiểm tra thương hiệu
        const brandResponse = await fetch(`http://localhost/sportswear-webstore/src/router/searchrouter.php?action=getBrandByName&name=${encodeURIComponent(searchInput)}`);
        const brandData = await brandResponse.json();
        console.log('Brand API response:', brandData); // Debug: Kiểm tra dữ liệu trả về

        if (brandData.status === 200 && brandData.data && brandData.data.ID) {
            console.log(`Redirecting to brand: ${brandData.data.ID} - ${brandData.data.name}`);
            window.location.href = `/sportswear-webstore/layout/client/search_results.php?brand=${brandData.data.ID}`;
            return;
        } else {
            console.log('No matching brand found or invalid response:', brandData.message || 'No message');
        }

        // Kiểm tra danh mục
        const categoryResponse = await fetch(`http://localhost/sportswear-webstore/src/router/searchrouter.php?action=getCategoryByName&name=${encodeURIComponent(searchInput)}`);
        const categoryData = await categoryResponse.json();
        console.log('Category API response:', categoryData); // Debug: Kiểm tra dữ liệu trả về

        if (categoryData.status === 200 && categoryData.data && categoryData.data.ID) {
            console.log(`Redirecting to category: ${categoryData.data.ID} - ${categoryData.data.name}`);
            window.location.href = `/sportswear-webstore/layout/client/search_results.php?category=${categoryData.data.ID}`;
            return;
        } else {
            console.log('No matching category found or invalid response:', categoryData.message || 'No message');
        }

        // Tìm kiếm thông thường
        console.log(`Performing general search for: ${searchInput}`);
        window.location.href = `/sportswear-webstore/layout/client/search_results.php?search=${encodeURIComponent(searchInput)}`;
    } catch (error) {
        console.error('Search error:', error);
        alert('Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.');
        window.location.href = `/sportswear-webstore/layout/client/search_results.php?search=${encodeURIComponent(searchInput)}`;
    }
}

if (searchForm) {
    searchForm.addEventListener('submit', performSearch);
}

// Lấy tham số từ URL
function getQueryParams() {
    const params = {};
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach((value, key) => {
        if (value) {
            if (key === 'price_start') {
                params['min_price'] = value;
            } else if (key === 'price_end') {
                params['max_price'] = value;
            } else {
                params[key] = value;
            }
        }
    });
    return params;
}

async function loadFilters() {
    try {
        const [brandsResponse, categoriesResponse] = await Promise.all([
            fetch('http://localhost/sportswear-webstore/src/router/searchrouter.php?action=getAllBrands'),
            fetch('http://localhost/sportswear-webstore/src/router/searchrouter.php?action=getAllCategories')
        ]);

        const brandsData = await brandsResponse.json();
        const categoriesData = await categoriesResponse.json();

        const brandSelect = document.querySelector('select[name="brand"]');
        const categorySelect = document.querySelector('select[name="category"]');
        const brandError = document.getElementById('brand-error');
        const categoryError = document.getElementById('category-error');

        // Lấy tham số từ URL
        const initialParams = getQueryParams();

        if (brandsData.status === 200 && Array.isArray(brandsData.data)) {
            brandSelect.innerHTML = '<option value="">Tất cả thương hiệu</option>';
            brandsData.data.forEach(brand => {
                const option = document.createElement('option');
                option.value = brand.ID;
                option.textContent = brand.name;
                if (initialParams.brand && String(initialParams.brand) === String(brand.ID)) {
                    option.selected = true;
                }
                brandSelect.appendChild(option);
            });
            brandError.style.display = 'none';
        } else {
            console.error('Lỗi tải thương hiệu:', brandsData.message || 'Không có dữ liệu');
            brandError.style.display = 'block';
        }

        if (categoriesData.status === 200 && Array.isArray(categoriesData.data)) {
            categorySelect.innerHTML = '<option value="">Tất cả danh mục</option>';
            categoriesData.data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.ID;
                option.textContent = category.name;
                if (initialParams.category && String(initialParams.category) === String(category.ID)) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
            categoryError.style.display = 'none';
        } else {
            console.error('Lỗi tải danh mục:', categoriesData.message || 'Không có dữ liệu');
            categoryError.style.display = 'block';
        }

        // Cập nhật giá trị cho các input khác
        document.querySelector('input[name="min_price"]').value = initialParams.min_price || '';
        document.querySelector('input[name="max_price"]').value = initialParams.max_price || '';
        document.querySelector('select[name="status"]').value = initialParams.status || '';
        document.querySelector('select[name="sort"]').value = initialParams.sort || 'newest';

        // Gọi updateResults với tham số từ URL
        if (Object.keys(initialParams).length > 0) {
            updateResults(initialParams);
        }
    } catch (error) {
        console.error('Lỗi khi tải bộ lọc:', error);
        document.getElementById('brand-error').style.display = 'block';
        document.getElementById('category-error').style.display = 'block';
    }
}


window.addEventListener('DOMContentLoaded', () => {
    loadFilters();
});

async function updateResults(params = {}) {
    const itemsPerPage = 12;
    params.page = params.page || 1;
    params.limit = itemsPerPage;

    if (params.price_start) params.min_price = params.price_start;
    if (params.price_end) params.max_price = params.price_end;
    delete params.price_start;
    delete params.price_end;

    const queryString = new URLSearchParams(params).toString();
    const apiUrl = `http://localhost/sportswear-webstore/src/router/searchrouter.php?action=searchProducts&${queryString}`;

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

        productList.innerHTML = paginatedProducts.length > 0
            ? paginatedProducts.map(product => {
                const imagePath = `/sportswear-webstore/img/products/${product.ID}.jpg`;
                const defaultImage = '/sportswear-webstore/img/products/default.jpg';
                const rating = parseFloat(product.rating || 0);
                const scaledRating = rating / 2; // Assuming rating is out of 10, scale to 5 stars
                const fullStars = Math.floor(scaledRating);
                const hasHalfStar = scaledRating % 1 >= 0.5;
                let ratingHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= fullStars) {
                        ratingHtml += '<i class="fas fa-star"></i>';
                    } else if (hasHalfStar && i === fullStars + 1) {
                        ratingHtml += '<i class="fas fa-star-half-alt"></i>'; // Use half-star icon
                    } else {
                        ratingHtml += '<i class="far fa-star"></i>';
                    }
                }

                const basePrice = parseFloat(product.price) || 0;
                const markupPercentage = parseFloat(product.markup_percentage) || 0;
                const sellingPrice = basePrice + (basePrice * markupPercentage / 100);
                const formattedPrice = Math.round(sellingPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' ₫';

                return `
                    <a href="/sportswear-webstore/layout/client/detailproduct.php?id=${product.ID}" class="product-card">
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
                            <span>(${scaledRating.toFixed(1)})</span>
                        </div>
                        <button class="buy-button" id="idProduct-${product.ID}" ${product.status === 'out_of_stock' ? 'disabled' : ''}>
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

            pagination.querySelectorAll('.page-link[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    params.page = parseInt(link.dataset.page);
                    updateResults(params);
                });
            });
        }
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Cuộn mượt mà
        });
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

        
    document.querySelectorAll('.buy-button').forEach(buyButton => {
        buyButton.addEventListener('click', function(event) {
            event.preventDefault();
            const productId = this.id.replace('idProduct-', '');
            themVaoGio(productId);
        });
    });
    async function themVaoGio(productId) {
        const quantity = 1;
        try {
            const result = await addCartDetail(productId, quantity);

            if (result.status === 200) {
                alert('Đã thêm sản phẩm vào giỏ');
            } else {
                alert('Có lỗi xảy ra: ' + (result.data?.error || 'Không rõ lỗi'));
            }
        } catch (error) {
            const status = error.response?.status;
            const message = error.response?.data?.error || 'Lỗi không xác định';

            if (status === 400) {
                alert('Lỗi 400 - Bad Request: ' + message);
            } else if (status === 401) {
                alert('Bạn chưa đăng nhập. Vui lòng đăng nhập lại.');
            } else {
                alert('Đã xảy ra lỗi: ' + message);
            }
        }

    }
}
async function getNameFromId(id, type) {
    try {
        const url = `http://localhost/sportswear-webstore/src/router/searchrouter.php?action=get${type}ById&id=${id}`;
        const response = await fetch(url);
        const data = await response.json();
        return (data.status === 200 && data.data && data.data.name) ? data.data.name : 'Không xác định';
    } catch (error) {
        console.error(`Lỗi khi lấy tên ${type.toLowerCase()}:`, error);
        return 'Không xác định';
    }
}

if (filterForm) {
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(filterForm);
        const params = {};
        formData.forEach((value, key) => {
            if (value) params[key] = value;
        });
        params.page = 1;
        updateResults(params);
    });

    const filterInputs = filterForm.querySelectorAll('.filter-select, .filter-input');
    filterInputs.forEach(input => {
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
}

