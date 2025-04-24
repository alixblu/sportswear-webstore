const COUPON_API_URL = '../../src/router/couponRouter.php';

const getAllCoupons = async () => {
    try {
        const response = await fetch(`${COUPON_API_URL}?action=getAllCoupons`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!response.ok) throw new Error('Không thể lấy danh sách mã giảm giá');

        const data = await response.json();
        return Array.isArray(data) ? data : data.data;
    } catch (error) {
        console.error('Error in getAllCoupons:', error);
        throw error;
    }
};

const getCouponById = async (id) => {
    const response = await fetch(`${COUPON_API_URL}?action=getCouponById&id=${id}`, {
        method: 'GET',
    });

    if (!response.ok) throw new Error('Không thể lấy thông tin mã giảm giá');

    return await response.json();
};

const createCoupon = async (name, percent, duration) => {
    const formData = new URLSearchParams();
    formData.append('action', 'createCoupon');
    formData.append('name', name);
    formData.append('percent', percent);
    formData.append('duration', duration);

    const response = await fetch(COUPON_API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString(),
    });

    if (!response.ok) throw new Error('Không thể tạo mã giảm giá');

    return await response.json();
};

const updateCoupon = async (id, name, percent, duration, status) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateCoupon');
    formData.append('id', id);
    formData.append('name', name);
    formData.append('percent', percent);
    formData.append('duration', duration);
    formData.append('status', status);

    const response = await fetch(COUPON_API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) throw new Error('Không thể cập nhật mã giảm giá');

    return await response.json();
};

const deleteCoupon = async (id) => {
    const response = await fetch(`${COUPON_API_URL}?action=deleteCoupon&id=${id}`, {
        method: 'DELETE',
    });

    if (!response.ok) throw new Error('Không thể xoá mã giảm giá');

    return await response.json();
};
