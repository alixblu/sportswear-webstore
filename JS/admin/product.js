const API_URL = 'http://localhost/sportwear/src/router/productRouter.php';

const getAllProducts = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllProducts`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Không thể lấy danh sách sản phẩm');
        }

        const data = await response.json();
        console.log('Raw API Response:', data); // Debug log

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
const getBrandById = async (id) => {
    const response = await fetch(`${API_URL}?action=getBrandById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
};
const updateProduct = async (
    id,
    categoryID,
    discountID,
    brandID,
    name,
    markup_percentage,
    rating,
    image,
    description,
    stock,
    status
) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateProduct');
    formData.append('id', id);
    formData.append('categoryID', categoryID);
    formData.append('discountID', discountID);
    formData.append('brandID', brandID);
    formData.append('name', name);
    formData.append('markup_percentage', markup_percentage);
    formData.append('rating', rating);
    formData.append('image', image);
    formData.append('description', description);
    formData.append('stock', stock);
    formData.append('status', status);

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
