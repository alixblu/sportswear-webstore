const API_URL = '../../src/router/productRouter.php';

const getAllCategories = async () => {
    const response = await fetch(`${API_URL}?action=getAllCategories`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách phân loại');
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

export {
    getAllCategories,
    getCategoryById,
    getCategoryByName,
}