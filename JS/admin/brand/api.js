const API_URL = '../../src/router/productRouter.php';

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
};

export {
    getAllBrands,
    getBrandById,
    getBrandByName,
}