const API_URL = '../../src/router/userRouter.php';

export const getAccountByUserId = async (userId) => {
    const response = await fetch(`${API_URL}?action=getAccountByUserId&userId=${userId}`, {
        method: 'GET',
    });

    if (!response.ok) {
        throw new Error('Không thể lấy thông tin tài khoản');
    }

    return await response.json();
};

export const getAllUsers = async () => {
    const response = await fetch(`${API_URL}?action=getAllUsers`, {
        method: 'GET',
        headers: {
           'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách người dùng');
    }

    return await response.json();
};

export const createDefaultAccount = async (name, email, phone, gender, roleID) => {
    const formData = new URLSearchParams();
    formData.append('action', 'defaultAccount');
    formData.append('name', name);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('gender', gender);
    formData.append('roleID', roleID);

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể tạo tài khoản');
    }

    return await response.json();
};

export const deleteUser = async (userId) => {
    const response = await fetch(`${API_URL}?action=deleteUsers&userId=${userId}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Không thể xoá người dùng');
    }

    return await response.json();
};

export const updateUser = async (id, name, email, passWord, phone, gender, roleID) => {
    const formData = new URLSearchParams();
    formData.append('action', 'updateUsers');
    formData.append('userId', id);
    formData.append('name', name);
    formData.append('email', email);
    formData.append('passWord', passWord);
    formData.append('phone', phone);
    formData.append('gender', gender);
    formData.append('roleID', roleID);

    const response = await fetch(API_URL, {
        method: 'PUT',
        body: formData.toString(),
    });

    if (!response.ok) {
        throw new Error('Không thể cập nhật người dùng');
    }

    return await response.json();
};
export const getAllRoles = async () => {
    const response = await fetch(`${API_URL}?action=getAllRoles`, {
        method: 'GET',
        headers: {
           'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Không thể lấy danh sách Roles');
    }

    return await response.json();
};

export const uploadFile = async (file) => {
    try {
        const formData = new FormData();
        formData.append("excel_file", file);
        const response = await fetch(`${API_URL}?action=uploadFile`, {
            method: "POST",
            body: formData
        });

        const result = await response.text();
        alert("Kết quả: " + result);
    } catch (err) {
        console.error("Lỗi:", err);
    }
};
