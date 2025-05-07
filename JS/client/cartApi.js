const CART_API_URL = '../../src/router/cartRouter.php';

const createCart = async (userId) => {
    const formData = new URLSearchParams();
    formData.append('action', 'createCart');
    formData.append('userId', userId);

    const response = await fetch(CART_API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể tạo giỏ hàng');
    }

    return await response.json();
};

const addCartDetail = async (productID, quantity) => {
    const formData = new URLSearchParams();
    formData.append('action', 'addCartDetail');
    formData.append('productID', productID);
    formData.append('quantity', quantity);

    const response = await fetch(CART_API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể thêm sản phẩm vào giỏ hàng');
    }

    return await response.json();
};


const getCartByUserId = async () => {
    const response = await fetch(`${CART_API_URL}?action=getCartByUserId`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy giỏ hàng theo userId');
    }

    return await response.json();
};

const updateTotalPrice = async (cartId, totalPrice) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateTotalPrice');
    formData.append('cartId', cartId);
    formData.append('totalPrice', totalPrice);

    const response = await fetch(CART_API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật tổng giá');
    }

    return await response.json();
};
