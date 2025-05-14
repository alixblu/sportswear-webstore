const CART_DETAIL_API_URL = '/sportswear-webstore/src/router/cartdetailrouter.php';


const getCartDetailsByCartID = async (cartID) => {
    const response = await fetch(`${CART_DETAIL_API_URL}?action=getCartDetailsByCartID&cartID=${cartID}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy chi tiết giỏ hàng');
    }

    return await response.json();
};

const updateCartDetailQuantity = async (cartDetailID, quantity) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateCartDetailQuantity');
    formData.append('cartDetailID', cartDetailID);
    formData.append('quantity', quantity);

    const response = await fetch(CART_DETAIL_API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật số lượng sản phẩm');
    }

    return await response.json();
};

const deleteCartDetail = async (cartDetailID) => {
    const formData = new URLSearchParams();
    formData.append('action', 'deleteCartDetail');
    formData.append('cartDetailID', cartDetailID);

    const response = await fetch(CART_DETAIL_API_URL, {
        method: 'DELETE',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể xoá sản phẩm khỏi giỏ hàng');
    }

    return await response.json();
};
