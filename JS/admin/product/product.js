const API_URL = '../../src/router/productRouter.php';

// ===================================== GET products ===================================== 
const getAllProducts = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllProducts`, {
            method: 'GET',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách sản phẩm');
        }

        const data = await response.json();

        let products = [];
        if (Array.isArray(data)) {
            products = data;
        } else if (data && Array.isArray(data.data)) {
            products = data.data;
        } else {
            throw new Error('Định dạng phản hồi từ server không hợp lệ');
        }

        // Thêm đường dẫn hình ảnh cho mỗi sản phẩm
        products = products.map(product => ({
            ...product,
            image: `/sportswear-webstore/img/products/${product.id}.jpg`
        }));

        return products;
    } catch (error) {
        console.error('Lỗi trong getAllProducts:', error);
        throw error;
    }
};

const getFilteredProducts = async (category=null, brand=null, status=null, min_price=null, max_price=null) => {
    // Không có thì lấy toàn bộ
    if(category == null && brand == null && status == null && min_price == null && max_price == null )
        return data = await getAllProducts();

    try {
        const url = new URL(API_URL)
        url.searchParams.set('action', 'getFilteredProducts')
        if (category) url.searchParams.set('category', category)
        if (brand) url.searchParams.set('brand', brand)
        if (status) url.searchParams.set('status', status)
        if (min_price) url.searchParams.set('min_price', min_price)
        if (max_price) url.searchParams.set('max_price', max_price)

        const response = await fetch(url.toString(),
            {
                method: 'GET',
                mode: 'cors',
                headers: {
                    'Content-Type':'application/json'
                }
            }
        )
        if(!response.ok)
            throw new Error("Không thể lấy sản phẩm theo tùy chọn !!!")
    
        const data = response.json()

        return Array.isArray(data) ? data : data.data
    } catch (error) {
        console.error('Lỗi, không thể lấy sản phẩm theo tùy chọn !!!', error)
        throw error
        
    }
}

const getProductById = async (id) => {
    const response = await fetch(`${API_URL}?action=getProductById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin sản phẩm');
    }

    return await response.json();
};

const getProductVariants = async (id) => {
    const response = await fetch(`${API_URL}?action=getProductVariants&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin biến thể sản phẩm');
    }

    return await response.json();
};


// ===================================== GET category ===================================== 
const getCategoryById = async (id) => {
    const response = await fetch(`${API_URL}?action=getCategoryById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
};

const getCategoryByName = async (name) => {
    const response = await fetch(`${API_URL}?action=getCategoryByName&name=${name}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
};

const getAllCategories = async () => {
    const response = await fetch(`${API_URL}?action=getAllCategories`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách phân loại');
    }

    return await response.json();
};

// ===================================== GET brand ===================================== 

const getAllBrands = async () => {
    const response = await fetch(`${API_URL}?action=getAllBrands`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách thương hiệu');
    }

    return await response.json();
};

const getBrandById = async (id) => {
    const response = await fetch(`${API_URL}?action=getBrandById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thương hiệu');
    }

    return await response.json();
};

const getBrandByName = async (name) => {
    const response = await fetch(`${API_URL}?action=getBrandByName&name=${name}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thương hiệu');
    }

    return await response.json();
};

// ===================================== Update & Delete product ===================================== 
const updateProduct = async (product) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateProduct');

    // Dynamically append properties from the product object
    for (const key in product) {
        formData.append(key, product[key]);
    }

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật sản phẩm');
    }

    return await response.json();
};

const updateProductStock = async (id) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateProductStock');
    formData.append('id', id);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật số lượng tồn kho');
    }

    return await response.json();
};

const deleteProduct = async (id) => {
    const response = await fetch(`${API_URL}?action=deleteProduct&id=${id}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Không thể xóa sản phẩm');
    }

    return await response.json();
};

// ===================================== Function to populate category dropdown ===================================== 
const populateCategoryFilter = async () => {
    try {
        const response = await getAllCategories();
        
        const categories = response.data || [];
        
        const categorySelect = document.getElementById('category');
        
        // Clear existing options except the first one
        while (categorySelect.options.length > 1) {
            categorySelect.remove(1);
        }

        // Add new options
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.ID;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Lỗi khi điền bộ lọc danh mục:', error);
    }
};

// ===================================== Function to populate brand dropdown ===================================== 
const populateBrandFilter = async () => {
    try {
        const response = await getAllBrands();
        
        const brands = response.data || [];
        
        const brandSelect = document.getElementById('brand');
        
        // Clear existing options except the first one
        while (brandSelect.options.length > 1) {
            brandSelect.remove(1);
        }

        // Add new options
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.ID;
            option.textContent = brand.name;
            brandSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Lỗi khi điền bộ lọc thương hiệu:', error);
    }
};

// ===================================== Function to load products with filters ===================================== 
const loadProducts = async () => {
    try {
        const normalize = (val) => (val == '' || val == 'all') ? null : val;

        let categoryFilter = normalize(document.getElementById('category').value);
        let brandFilter = normalize(document.getElementById('brand').value);
        let statusFilter = normalize(document.getElementById('status').value);
        let startPriceFilter = normalize(document.getElementById('priceStart').value)
        let endPriceFilter = normalize(document.getElementById('priceEnd').value)

        let filteredProducts = await getFilteredProducts(categoryFilter, brandFilter, statusFilter, startPriceFilter, endPriceFilter);

        const productGrid = document.getElementById('productGrid')
        productGrid.innerHTML = '';
        filteredProducts.forEach(product => {
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
        console.error('Lỗi khi tải sản phẩm:', error);
    }
};

// ===================================== Initialize filters when page loads===================================== 
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Populate category filter
        await populateCategoryFilter();
        
        // Populate brand filter
        await populateBrandFilter();
        
        // Set status filter options
        const statusSelect = document.getElementById('status');
        
        // Keep the first "All Status" option
        while (statusSelect.options.length > 1) {
            statusSelect.remove(1);
        }
        
        const statusOptions = [
            { value: 'in_stock', text: 'Còn hàng' },
            { value: 'out_of_stock', text: 'Hết hàng' },
            { value: 'discontinued', text: 'Ngừng kinh doanh' }
        ];
        
        statusOptions.forEach(status => {
            const option = document.createElement('option');
            option.value = status.value;
            option.textContent = status.text;
            statusSelect.appendChild(option);
        });

        // Load products after filters are set
        loadProducts();

        // Add event listeners for filter changes
        document.getElementById('category').addEventListener('change', loadProducts);
        document.getElementById('brand').addEventListener('change', loadProducts);
        document.getElementById('status').addEventListener('change', loadProducts);
    } catch (error) {
        console.error('Lỗi khi khởi tạo bộ lọc:', error);
    }
});