const API_URL = '../../src/router/productRouter.php';

const getAllProducts = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllProducts`, {
            method: 'GET',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách sản phẩm');
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Phản hồi không phải là JSON');
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

const getAllCategories = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllCategories`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách phân loại');
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Phản hồi không phải là JSON');
        }

        return await response.json();
    } catch (error) {
        console.error('Lỗi trong getAllCategories:', error);
        throw error;
    }
};

const getAllBrands = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllBrands`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách thương hiệu');
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Phản hồi không phải là JSON');
        }

        return await response.json();
    } catch (error) {
        console.error('Lỗi trong getAllBrands:', error);
        throw error;
    }
};

// Function to populate category dropdown
const populateCategoryFilter = async () => {
    try {
        const response = await getAllCategories();
        
        const categories = response.data || [];
        
        const categorySelect = document.getElementById('category');
        if (!categorySelect) {
            console.error('Không tìm thấy phần tử category select');
            return;
        }
        
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

// Function to populate brand dropdown
const populateBrandFilter = async () => {
    try {
        const response = await getAllBrands();
        
        const brands = response.data || [];
        
        const brandSelect = document.getElementById('brand');
        if (!brandSelect) {
            console.error('Không tìm thấy phần tử brand select');
            return;
        }
        
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

// Function to load products with filters
const loadProducts = async () => {
    try {
        const products = await getAllProducts();
        const categoryFilter = document.getElementById('category').value;
        const brandFilter = document.getElementById('brand').value;
        const statusFilter = document.getElementById('status').value;

        let filteredProducts = products;

        if (categoryFilter !== 'all') {
            filteredProducts = filteredProducts.filter(product => product.categoryID === categoryFilter);
        }
        if (brandFilter !== 'all') {
            filteredProducts = filteredProducts.filter(product => product.brandID === brandFilter);
        }
        if (statusFilter !== 'all') {
            filteredProducts = filteredProducts.filter(product => product.status === statusFilter);
        }

        const productContainer = document.getElementById('product-list');
        productContainer.innerHTML = '';

        filteredProducts.forEach(product => {
            const productElement = document.createElement('div');
            productElement.className = 'product-item';
            productElement.innerHTML = `
                <img src="${product.image}" alt="${product.name}" style="max-width: 100px;" loading="lazy">
                <h3>${product.name}</h3>
                <p>ID: ${product.id}</p>
                <p>Giá: ${product.price || 'Chưa có giá'}</p>
                <p>Trạng thái: ${product.status}</p>
            `;
            productContainer.appendChild(productElement);
        });
    } catch (error) {
        console.error('Lỗi khi tải sản phẩm:', error);
    }
};

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', async () => {
    try {
        console.log('Khởi tạo bộ lọc...');
        
        // Populate category filter
        await populateCategoryFilter();
        
        // Populate brand filter
        await populateBrandFilter();
        
        // Set status filter options
        const statusSelect = document.getElementById('status');
        console.log('Phần tử select trạng thái:', statusSelect);
        
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
            console.log('Thêm trạng thái:', status);
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