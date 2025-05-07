const API_URL = '../../../src/router/orderrouter.php';

// Lấy tất cả đơn hàng
const getAllOrders = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllOrders`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách đơn hàng');

        const data = await response.json();
        console.log('API response:', data);
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllOrders:', error);
        throw error;
    }
};
// Lấy chi tiết một đơn hàng theo ID
const getOrderDetails = async (orderID) => {
    const response = await fetch(`${API_URL}?action=getOrderDetails&id=${orderID}`, {
        method: 'GET',
    });

    if (!response.ok) throw new Error('Không thể lấy chi tiết đơn hàng');

    return await response.json();
};

// Tìm kiếm đơn hàng theo nhiều tiêu chí
const searchOrders = async ({ orderID = '', customerName = '', fromDate = '', toDate = '' }) => {
    const query = new URLSearchParams({
        action: 'searchOrders',
        orderID,
        customerName,
        fromDate,
        toDate,
    });

    const response = await fetch(`${API_URL}?${query.toString()}`, {
        method: 'GET',
    });

    if (!response.ok) throw new Error('Không thể tìm kiếm đơn hàng');

    return await response.json();
};

// Cập nhật trạng thái đơn hàng
const updateOrderStatus = async (orderID, status) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateOrderStatus');
    formData.append('orderID', orderID);
    formData.append('status', status);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) throw new Error('Không thể cập nhật trạng thái đơn hàng');

    return await response.json();
};

// Cập nhật thông tin chi tiết đơn hàng (receiver, địa chỉ, phương thức thanh toán)
const updateOrderDetails = async ({ orderID, receiverName, address, phone, email, paymentMethodID }) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateOrderDetails');
    formData.append('orderID', orderID);
    formData.append('receiverName', receiverName);
    formData.append('address', address);
    formData.append('phone', phone);
    formData.append('email', email);
    formData.append('paymentMethodID', paymentMethodID);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) throw new Error('Không thể cập nhật thông tin đơn hàng');

    return await response.json();
};
 