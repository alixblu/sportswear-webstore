const ORDER_API_URL = '/sportswear-webstore/src/router/orderrouter.php';

// Lấy tất cả đơn hàng
const getAllOrders = async () => {
    try {
        const response = await fetch(`${ORDER_API_URL}?action=getAllOrders`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách đơn hàng');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllOrders:', error);
        throw error;
    }
};


const getOrdersByCustomer = async () => {
    try {
        const response = await fetch(`${ORDER_API_URL}?action=getOrdersByCustomer`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách đơn hàng');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getOrdersByCustomer:', error);
        throw error;
    }
};



// Tìm kiếm đơn hàng theo nhiều tiêu chí
const searchOrders = async ({status = '', address = '', fromDate = '', toDate = '' }) => {
    const query = new URLSearchParams({
        action: 'searchOrders',
        status,
        address: address.trim(), // Loại bỏ khoảng trắng thừa
        fromDate: fromDate || '',
        toDate: toDate || ''
    });

    console.log('API Query:', query.toString()); // Debug

    try {
        const response = await fetch(`${ORDER_API_URL}?${query.toString()}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Lỗi khi tìm kiếm đơn hàng');
        }

        return await response.json();
    } catch (error) {
        console.error('Search Orders Error:', error);
        throw error;
    }
};
// Cập nhật trạng thái đơn hàng
const updateOrderStatus = async (ID, status) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateOrderStatus');
    formData.append('ID', ID);
    formData.append('status', status);

    const response = await fetch(ORDER_API_URL, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Không thể cập nhật trạng thái đơn hàng');
    }

    return await response.json();
};



const createOrder = async (receiverName, address, phone, idCoupon = '', payment = '') => {
    const formData = new URLSearchParams();
    formData.append('action', 'createOrders');
    formData.append('receiverName', receiverName);
    formData.append('address', address);
    formData.append('phone', phone);
    formData.append('idCoupon', idCoupon);
    formData.append('payment',payment);

    const response = await fetch(ORDER_API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Không thể tạo đơn hàng');
    }

    return await response.json();
};



const getOrderDetails = async (orderID) => {
    const url = `${ORDER_API_URL}?action=getOrderDetails&id=${orderID}`;

    const response = await fetch(url, {
        method: 'GET',
    });

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Không thể lấy chi tiết đơn hàng');
    }

    return await response.json();
};
