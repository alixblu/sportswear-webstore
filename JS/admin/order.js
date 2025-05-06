const Order_API_URL = '../../src/router/orderRouter.php';

const getAllOrder = async () => {
    try {
        const response = await fetch(`${Order_API_URL}?action=getAllOrder`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách đơn hàng');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllOrder:', error);
        throw error;
    }
};

const getOrderById = async (orderID) => {
    try {
        const response = await fetch(`${Order_API_URL}?action=getOrderById&orderID=${orderID}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy thông tin đơn hàng');

        const data = await response.json();
        if (data.error) throw new Error(data.error); // Handle backend errors like "Order not found"
        return data; // Expecting an array of order details
    } catch (error) {
        console.error('Error in getOrderById:', error);
        throw error;
    }
};
const updateOrderStatus = async (orderID, status) => {
    try {
        const response = await fetch(`${Order_API_URL}?action=updateOrderStatus`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderID, status }),
        });

        if (!response.ok) throw new Error('Không thể cập nhật trạng thái đơn hàng');

        const data = await response.json();
        if (data.error) throw new Error(data.error); // Handle backend errors
        return data; // Expecting a success message
    } catch (error) {
        console.error('Error in updateOrderStatus:', error);
        throw error;
    }
};


