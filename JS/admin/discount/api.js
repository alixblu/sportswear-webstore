const API_URL = '../../src/router/productRouter.php';

const getAllDiscounts = async () => {
    const response = await fetch(`${API_URL}?action=getAllDiscounts`, {
        method: 'GET'
    })

    if(!response.ok)
        throw new Error("Không thể lấy danh sách giảm giả")
    return await response.json()
}

const getDiscountByID = async (id) => {
    const response = await fetch(`${API_URL}?action=getDiscountById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy phân loại sản phẩm');
    }

    return await response.json();
}

const getDiscountByName = async () => {

}

export {
    getAllDiscounts,
    getDiscountByID,
    getDiscountByName,
}
