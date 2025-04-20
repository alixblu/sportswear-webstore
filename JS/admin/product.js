const API_URL = 'http://localhost/sportwear/src/router/productrouter.php';
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

        // Check if data is an array or has a data property
        if (Array.isArray(data)) {
            return data;
        } else if (data && Array.isArray(data.data)) {
            return data.data;
        } else {
            throw new Error('Invalid response format from server');
        }
    } catch (error) {
        console.error('Error in getAllProducts:', error);
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
}
const getBrandById = async (id) => {
    const response = await fetch(`${API_URL}?action=getBrandById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
};
const getBrandByName = async (name) => {
    const response = await fetch(`${API_URL}?action=getBrandByName&name=${name}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
}
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
        throw new Error('Không thể xoá sản phẩm');
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

const getAllBrands = async () => {
    const response = await fetch(`${API_URL}?action=getAllBrands`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách thương hiệu');
    }

    return await response.json();
};

// Function to populate category dropdown
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
        console.error('Error populating category filter:', error);
    }
};

// Function to populate brand dropdown
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
        console.error('Error populating brand filter:', error);
    }
};

// Initialize filters when page loads
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
            { value: 'in_stock', text: 'In Stock' },
            { value: 'out_of_stock', text: 'Out of Stock' },
            { value: 'discontinued', text: 'Discontinued' }
        ];
        
        statusOptions.forEach(status => {
            const option = document.createElement('option');
            option.value = status.value;
            option.textContent = status.text;
            statusSelect.appendChild(option);
        });

        // Load products after filters are set
        loadProducts();
    } catch (error) {
        console.error('Error initializing filters:', error);
    }
});

