const API_URL = '../../../src/router/orderrouter.php';

// Lấy tất cả đơn hàng
const getAllOrders = async () => {
    try {
        const response = await fetch(`${API_URL}?action=getAllOrders`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Không thể lấy danh sách đơn hàng: ${errorText}`);
        }

        const data = await response.json();
        console.log('API response:', data);
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllOrders:', error.message);
        throw error;
    }
};

// Lấy chi tiết một đơn hàng theo ID
const getOrderDetails = async (orderID) => {
    try {
        const response = await fetch(`${API_URL}?action=getOrderDetails&id=${orderID}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Không thể lấy chi tiết đơn hàng: ${errorText}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error in getOrderDetails:', error.message);
        throw error;
    }
};

// Tìm kiếm đơn hàng theo nhiều tiêu chí
const searchOrders = async ({ orderID = '', customerName = '', fromDate = '', toDate = '' }) => {
    try {
        const query = new URLSearchParams({
            action: 'searchOrders',
            orderID,
            customerName,
            fromDate,
            toDate,
        });

        const response = await fetch(`${API_URL}?${query.toString()}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Không thể tìm kiếm đơn hàng: ${errorText}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error in searchOrders:', error.message);
        throw error;
    }
};

// Cập nhật trạng thái đơn hàng
const updateOrderStatus = async (orderID, status) => {
        const formData = new URLSearchParams();
        formData.append('action', 'updateOrderStatus');
        formData.append('orderID', orderID);
        formData.append('status', status);
        console.log('Updating order:', { orderID, status });

        const response = await fetch(API_URL, {
            method: 'PUT',
            body: formData.toString(),
        });
    
        if (!response.ok) throw new Error('Không thể cập nhật trạng thái đơn hàng');
    
        return await response.json();
};
// Cập nhật thông tin chi tiết đơn hàng (receiver, địa chỉ, phương thức thanh toán)
const updateOrderDetails = async ({ orderID, receiverName, address, phone, email, paymentMethodID }) => {
    try {
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
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString(),
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Không thể cập nhật thông tin đơn hàng: ${errorText}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error in updateOrderDetails:', error.message);
        throw error;
    }
};